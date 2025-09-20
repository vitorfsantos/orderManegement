<?php

namespace App\Modules\Users\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Users\Requests\UpdateUserRequest;
use App\Modules\Users\Services\EditUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EditUserController extends Controller
{
  public function __construct(
    private EditUserService $editUserService
  ) {}

  public function show(User $user): View|JsonResponse
  {
    if (request()->expectsJson()) {
      $html = view('Users.partials.user-details', compact('user'))->render();
      return response()->json([
        'success' => true,
        'html' => $html
      ]);
    }

    return $this->editUserService->show($user);
  }

  public function edit(User $user): View|JsonResponse
  {
    if (request()->expectsJson()) {
      return response()->json([
        'success' => true,
        'user' => [
          'id' => $user->id,
          'name' => $user->name,
          'email' => $user->email,
          'role' => $user->role,
          'created_at' => $user->created_at->format('d/m/Y H:i'),
          'updated_at' => $user->updated_at->format('d/m/Y H:i'),
        ]
      ]);
    }

    return $this->editUserService->showForm($user);
  }

  public function update(UpdateUserRequest $request, User $user): RedirectResponse|JsonResponse
  {
    if ($request->ajax()) {
      try {
        $this->editUserService->updateUser($user, $request->validated());
        return response()->json([
          'success' => true,
          'message' => 'Usuário atualizado com sucesso!'
        ]);
      } catch (\Exception $e) {
        return response()->json([
          'success' => false,
          'message' => 'Erro ao atualizar usuário: ' . $e->getMessage()
        ], 422);
      }
    }

    return $this->editUserService->execute($user, $request->validated());
  }
}
