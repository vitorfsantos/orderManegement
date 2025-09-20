<?php

namespace App\Modules\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
  public function authorize(): bool
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'email' => 'required|email',
      'password' => 'required|string',
      'remember' => 'boolean',
    ];
  }

  public function messages(): array
  {
    return [
      'email.required' => 'O campo email é obrigatório.',
      'email.email' => 'O email deve ser um endereço de email válido.',
      'password.required' => 'O campo senha é obrigatório.',
    ];
  }
}
