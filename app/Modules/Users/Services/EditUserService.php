<?php

namespace App\Modules\Users\Services;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EditUserService
{
  public function show(User $user): View
  {
    return view('Users.show', compact('user'));
  }

  public function showForm(User $user): View
  {
    return view('Users.edit', compact('user'));
  }

  public function updateUser(User $user, array $data): User
  {
    $userData = [
      'name' => $data['name'],
      'email' => $data['email'],
      'role' => $data['role'],
    ];

    if (!empty($data['password'])) {
      $userData['password'] = bcrypt($data['password']);
    }

    $user->update($userData);
    return $user->fresh();
  }

  public function execute(User $user, array $data): RedirectResponse
  {
    try {
      $this->updateUser($user, $data);

      return redirect()
        ->route('users.show', $user)
        ->with('success', 'Usuário atualizado com sucesso!');
    } catch (\Exception $e) {
      return back()
        ->withInput()
        ->with('error', 'Erro ao atualizar usuário: ' . $e->getMessage());
    }
  }
}
