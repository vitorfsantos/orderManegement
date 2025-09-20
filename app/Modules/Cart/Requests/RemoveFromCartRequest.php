<?php

namespace App\Modules\Cart\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class RemoveFromCartRequest extends FormRequest
{
  public function authorize(): bool
  {
    return Auth::check();
  }

  public function rules(): array
  {
    return [
      'produto_id' => 'required|string',
    ];
  }

  public function messages(): array
  {
    return [
      'produto_id.required' => 'O ID do produto é obrigatório.',
    ];
  }
}
