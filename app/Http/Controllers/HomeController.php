<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function dashboard()
    {
        $user =  Auth::user();
        if ($user) {
            if ($user->can('view panel')) {
                return redirect()->route('panel.dashboard');
            } else {
                return redirect()->route('home');
            }
        } else {
            return \redirect()->route('home');
        }
    }
}
