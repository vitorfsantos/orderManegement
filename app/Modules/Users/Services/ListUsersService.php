<?php

namespace App\Modules\Users\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class ListUsersService
{
  public function execute(Request $request): View
  {
    $search = $request->get('search');
    $role = $request->get('role');

    if ($search) {
      $users = User::where('name', 'like', "%{$search}%")
        ->orWhere('email', 'like', "%{$search}%")
        ->orderBy('name')
        ->paginate(15);
    } elseif ($role) {
      $users = User::where('role', $role)
        ->orderBy('name')
        ->paginate(15);
    } else {
      $users = User::orderBy('name')->paginate(15);
    }

    return view('Users.index', compact('users', 'search', 'role'));
  }
}
