<?php

namespace App\Modules\Auth\Services;

use Illuminate\View\View;

class ShowLoginService
{
  public function execute(): View
  {
    return view('Auth.login');
  }
}
