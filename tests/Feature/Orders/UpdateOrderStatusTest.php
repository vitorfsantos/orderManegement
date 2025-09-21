<?php

namespace Tests\Feature\Orders;

use App\Models\User;
use App\Modules\Orders\Models\Order;
use App\Modules\Orders\Models\OrderItem;
use App\Modules\Products\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateOrderStatusTest extends TestCase
{
  use RefreshDatabase;

  private User $admin;
  private User $user;
  private Product $product;
  private Order $order;

  protected function setUp(): void
  {
    parent::setUp();

    // Criar usuário admin
    $this->admin = User::factory()->create(['role' => 'admin']);

    // Criar usuário comum
    $this->user = User::factory()->create(['role' => 'customer']);

    // Criar produto com estoque
    $this->product = Product::create([
      'name' => 'Produto Teste',
      'slug' => 'produto-teste',
      'price' => 100.00,
      'stock' => 10,
      'active' => true,
    ]);

    // Criar pedido
    $this->order = Order::create([
      'user_id' => $this->user->id,
      'total' => 200.00,
      'status' => 'pending'
    ]);

    // Criar item do pedido
    OrderItem::create([
      'order_id' => $this->order->id,
      'product_id' => $this->product->id,
      'quantity' => 2,
      'unit_price' => 100.00
    ]);

    // Simular que o estoque foi subtraído (como acontece na finalização do pedido)
    $this->product->decrement('stock', 2);
  }

  public function test_admin_can_cancel_order_and_stock_is_returned()
  {
    // Verificar estoque inicial (deve ser 8, pois subtraímos 2)
    $this->assertEquals(8, $this->product->fresh()->stock);

    // Admin cancela o pedido
    $response = $this->actingAs($this->admin)
      ->patch(route('orders.update-status', $this->order->id), [
        'status' => 'cancelled'
      ]);

    // Verificar redirecionamento
    $response->assertRedirect();
    $response->assertSessionHas('success');

    // Verificar que o pedido foi cancelado
    $this->order->refresh();
    $this->assertEquals('cancelled', $this->order->status);

    // Verificar que o estoque foi devolvido (deve voltar para 10)
    $this->product->refresh();
    $this->assertEquals(10, $this->product->stock);
  }

  public function test_cannot_cancel_already_cancelled_order()
  {
    // Cancelar o pedido primeiro
    $this->order->update(['status' => 'cancelled']);

    // Tentar cancelar novamente
    $response = $this->actingAs($this->admin)
      ->patch(route('orders.update-status', $this->order->id), [
        'status' => 'cancelled'
      ]);

    // Verificar erro
    $response->assertRedirect();
    $response->assertSessionHas('error');
    $response->assertSessionHas('error', 'Este pedido já está cancelado.');
  }

  public function test_non_admin_cannot_update_order_status()
  {
    $response = $this->actingAs($this->user)
      ->patch(route('orders.update-status', $this->order->id), [
        'status' => 'cancelled'
      ]);

    // Verificar que retorna 403 (Forbidden)
    $response->assertStatus(403);
  }

  public function test_invalid_status_returns_error()
  {
    $response = $this->actingAs($this->admin)
      ->patch(route('orders.update-status', $this->order->id), [
        'status' => 'invalid_status'
      ]);

    // Verificar que há algum tipo de erro (pode ser redirecionamento ou 422)
    $this->assertTrue($response->status() === 302 || $response->status() === 422);
  }

  public function test_changing_from_cancelled_to_paid_does_not_affect_stock()
  {
    // Cancelar o pedido primeiro (estoque volta para 10)
    $this->order->update(['status' => 'cancelled']);
    $this->product->increment('stock', 2); // Simular retorno do estoque
    $this->assertEquals(10, $this->product->fresh()->stock);

    // Mudar para pago
    $response = $this->actingAs($this->admin)
      ->patch(route('orders.update-status', $this->order->id), [
        'status' => 'paid'
      ]);

    // Verificar sucesso
    $response->assertRedirect();
    $response->assertSessionHas('success');

    // Verificar que o pedido foi marcado como pago
    $this->order->refresh();
    $this->assertEquals('paid', $this->order->status);

    // Verificar que o estoque não foi alterado (deve continuar 10)
    $this->product->refresh();
    $this->assertEquals(10, $this->product->stock);
  }
}
