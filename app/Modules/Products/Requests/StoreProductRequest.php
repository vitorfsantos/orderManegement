<?php

namespace App\Modules\Products\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StoreProductRequest extends FormRequest
{
  public function authorize(): bool
  {
    return Auth::check() && Auth::user()->isAdmin();
  }

  public function rules(): array
  {
    return [
      'name' => 'required|string|max:255',
      'price' => 'required|numeric|min:0.01',
      'stock' => 'required|integer|min:0',
      'active' => 'boolean',
    ];
  }

  public function messages(): array
  {
    return [
      'name.required' => 'O campo nome é obrigatório.',
      'name.string' => 'O nome deve ser um texto válido.',
      'name.max' => 'O nome não pode ter mais de 255 caracteres.',
      'price.required' => 'O campo preço é obrigatório.',
      'price.numeric' => 'O preço deve ser um número válido.',
      'price.min' => 'O preço deve ser maior que zero.',
      'stock.required' => 'O campo estoque é obrigatório.',
      'stock.integer' => 'O estoque deve ser um número inteiro.',
      'stock.min' => 'O estoque não pode ser negativo.',
      'active.boolean' => 'O campo ativo deve ser verdadeiro ou falso.',
    ];
  }
}
