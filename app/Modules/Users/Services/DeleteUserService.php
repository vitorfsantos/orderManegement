<?php

namespace App\Modules\Users\Services;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class DeleteUserService
{
  public function deleteUser(User $user): bool
  {
    // Não permitir que o admin delete a si mesmo
    if ($user->id === Auth::id()) {
      throw new \Exception('Você não pode deletar sua própria conta.');
    }

    return $user->delete();
  }

  public function execute(User $user): RedirectResponse
  {
    try {
      $this->deleteUser($user);

      return redirect()
        ->route('users.index')
        ->with('success', 'Usuário removido com sucesso!');
    } catch (\Exception $e) {
      return back()
        ->with('error', 'Erro ao remover usuário: ' . $e->getMessage());
    }
  }
}
