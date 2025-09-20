<?php

namespace App\Modules\Users\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Users\Requests\StoreUserRequest;
use App\Modules\Users\Services\CreateUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CreateUserController extends Controller
{
  public function __construct(
    private CreateUserService $createUserService
  ) {}

  public function create(): View
  {
    return $this->createUserService->showForm();
  }

  public function store(StoreUserRequest $request): RedirectResponse|JsonResponse
  {
    if ($request->expectsJson()) {
      try {
        $user = $this->createUserService->createUser($request->validated());
        return response()->json([
          'success' => true,
          'message' => 'Usuário criado com sucesso!',
          'user' => $user
        ]);
      } catch (\Exception $e) {
        return response()->json([
          'success' => false,
          'message' => 'Erro ao criar usuário: ' . $e->getMessage()
        ], 422);
      }
    }

    return $this->createUserService->execute($request->validated());
  }
}
