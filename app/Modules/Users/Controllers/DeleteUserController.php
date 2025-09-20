<?php

namespace App\Modules\Users\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Users\Services\DeleteUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class DeleteUserController extends Controller
{
  public function __construct(
    private DeleteUserService $deleteUserService
  ) {}

  public function destroy(User $user): RedirectResponse|JsonResponse
  {
    if (request()->header('X-Requested-With') === 'XMLHttpRequest') {
      try {
        $this->deleteUserService->deleteUser($user);
        return response()->json([
          'success' => true,
          'message' => 'Usuário removido com sucesso!'
        ]);
      } catch (\Exception $e) {
        return response()->json([
          'success' => false,
          'message' => $e->getMessage()
        ], 422);
      }
    }

    return $this->deleteUserService->execute($user);
  }
}
