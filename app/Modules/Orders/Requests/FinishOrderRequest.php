<?php

namespace App\Modules\Orders\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FinishOrderRequest extends FormRequest
{
  public function authorize(): bool
  {
    return Auth::check();
  }

  public function rules(): array
  {
    return [
      // Não precisamos de validações específicas pois o carrinho já está na sessão
      // A validação de estoque será feita no serviço
    ];
  }

  public function messages(): array
  {
    return [
      // Mensagens de erro serão tratadas no serviço
    ];
  }
}
