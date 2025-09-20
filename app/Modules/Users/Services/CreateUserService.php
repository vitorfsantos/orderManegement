<?php

namespace App\Modules\Users\Services;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CreateUserService
{
  public function showForm(): View
  {
    return view('Users.create');
  }

  public function createUser(array $data): User
  {
    return User::create([
      'name' => $data['name'],
      'email' => $data['email'],
      'password' => bcrypt($data['password']),
      'role' => $data['role'],
    ]);
  }

  public function execute(array $data): RedirectResponse
  {
    try {
      $user = $this->createUser($data);

      return redirect()
        ->route('users.show', $user)
        ->with('success', 'Usuário criado com sucesso!');
    } catch (\Exception $e) {
      return back()
        ->withInput()
        ->with('error', 'Erro ao criar usuário: ' . $e->getMessage());
    }
  }
}
