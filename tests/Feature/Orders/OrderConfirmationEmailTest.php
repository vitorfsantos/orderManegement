<?php

namespace Tests\Feature\Orders;

use App\Models\User;
use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Models\OrderItem;
use App\Modules\Orders\Services\UpdateOrderStatusService;
use App\Modules\Products\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrderConfirmationEmailTest extends TestCase
{
  use RefreshDatabase;

  protected function setUp(): void
  {
    parent::setUp();
    Mail::fake();
  }

  public function test_sends_confirmation_email_when_order_status_changes_to_paid()
  {
    // Arrange
    $admin = User::factory()->create(['role' => 'admin']);
    $customer = User::factory()->create(['role' => 'customer']);

    $product = Product::factory()->create([
      'name' => 'Produto Teste',
      'price' => 100.00,
      'stock' => 10
    ]);

    $order = Order::factory()->create([
      'user_id' => $customer->id,
      'total' => 100.00,
      'status' => 'pending'
    ]);

    OrderItem::factory()->create([
      'order_id' => $order->id,
      'product_id' => $product->id,
      'quantity' => 1,
      'unit_price' => 100.00
    ]);

    $updateOrderStatusService = new UpdateOrderStatusService();

    // Act
    $this->actingAs($admin);
    $response = $updateOrderStatusService->execute($order->id, 'paid');

    // Assert
    Mail::assertSent(\App\Modules\Orders\Mail\OrderConfirmationMail::class, function ($mail) use ($order, $customer) {
      return $mail->order->id === $order->id &&
        $mail->hasTo($customer->email);
    });

    $this->assertEquals('paid', $order->fresh()->status);
    $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
  }

  public function test_does_not_send_email_when_order_status_changes_to_pending()
  {
    // Arrange
    $admin = User::factory()->create(['role' => 'admin']);
    $customer = User::factory()->create(['role' => 'customer']);

    $order = Order::factory()->create([
      'user_id' => $customer->id,
      'status' => 'paid'
    ]);

    $updateOrderStatusService = new UpdateOrderStatusService();

    // Act
    $this->actingAs($admin);
    $updateOrderStatusService->execute($order->id, 'pending');

    // Assert
    Mail::assertNotSent(\App\Modules\Orders\Mail\OrderConfirmationMail::class);
  }

  public function test_does_not_send_email_when_order_status_changes_to_cancelled()
  {
    // Arrange
    $admin = User::factory()->create(['role' => 'admin']);
    $customer = User::factory()->create(['role' => 'customer']);

    $order = Order::factory()->create([
      'user_id' => $customer->id,
      'status' => 'pending'
    ]);

    $updateOrderStatusService = new UpdateOrderStatusService();

    // Act
    $this->actingAs($admin);
    $updateOrderStatusService->execute($order->id, 'cancelled');

    // Assert
    Mail::assertNotSent(\App\Modules\Orders\Mail\OrderConfirmationMail::class);
  }

  public function test_handles_email_sending_failure_gracefully()
  {
    // Arrange
    $admin = User::factory()->create(['role' => 'admin']);
    $customer = User::factory()->create(['role' => 'customer']);

    $order = Order::factory()->create([
      'user_id' => $customer->id,
      'status' => 'pending'
    ]);

    // Simular falha no envio do email
    Mail::shouldReceive('to->send')
      ->andThrow(new \Exception('Email service unavailable'));

    $updateOrderStatusService = new UpdateOrderStatusService();

    // Act & Assert - não deve lançar exceção
    $this->actingAs($admin);
    $response = $updateOrderStatusService->execute($order->id, 'paid');

    // O pedido ainda deve ser atualizado mesmo se o email falhar
    $this->assertEquals('paid', $order->fresh()->status);
    $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
  }
}
