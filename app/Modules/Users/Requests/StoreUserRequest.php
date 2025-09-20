<?php

namespace App\Modules\Users\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
  public function authorize(): bool
  {
    return Auth::check() && Auth::user()->isAdmin();
  }

  public function rules(): array
  {
    return [
      'name' => 'required|string|max:255',
      'email' => 'required|email|unique:users,email',
      'password' => 'required|string|min:8|confirmed',
      'role' => ['required', Rule::in(['admin', 'customer'])],
    ];
  }

  public function messages(): array
  {
    return [
      'name.required' => 'O campo nome é obrigatório.',
      'name.string' => 'O nome deve ser um texto válido.',
      'name.max' => 'O nome não pode ter mais de 255 caracteres.',
      'email.required' => 'O campo email é obrigatório.',
      'email.email' => 'O email deve ser um endereço de email válido.',
      'email.unique' => 'Este email já está sendo usado.',
      'password.required' => 'O campo senha é obrigatório.',
      'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
      'password.confirmed' => 'A confirmação da senha não confere.',
      'role.required' => 'O campo perfil é obrigatório.',
      'role.in' => 'O perfil deve ser admin ou customer.',
    ];
  }
}
