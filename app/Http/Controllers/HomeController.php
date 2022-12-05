<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;

class HomeController extends Controller
{
    /**
     * Редирект пользователя после логина
     * @return RedirectResponse
     */
    public function home(): RedirectResponse
    {
        $roles = request()->user()->getRoleNames();

        if ($roles->contains(User::MANAGER_ROLE) || $roles->contains(User::ADMIN_ROLE)) {
            return redirect('/orders');
        }
        if ($roles->contains(User::ClENT_ROLE)) {
            return redirect('/orders/create');
        }
        return redirect()->back();
    }
}
