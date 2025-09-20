<?php

namespace App\Modules\Cart\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AddToCartRequest extends FormRequest
{
  public function authorize(): bool
  {
    return Auth::check();
  }

  public function rules(): array
  {
    return [
      'produto_id' => 'required|exists:products,id',
      'quantidade' => 'required|integer|min:1',
    ];
  }

  public function messages(): array
  {
    return [
      'produto_id.required' => 'O ID do produto é obrigatório.',
      'produto_id.exists' => 'O produto selecionado não existe.',
      'quantidade.required' => 'A quantidade é obrigatória.',
      'quantidade.integer' => 'A quantidade deve ser um número inteiro.',
      'quantidade.min' => 'A quantidade deve ser pelo menos 1.',
    ];
  }
}
