<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\User;

class WelcomeController extends Controller
{
    public function index()
    {
        $menus   = Menu::with('seller')->where('status', 'tersedia')->inRandomOrder()->take(8)->get();
        $sellers = User::where('role', 'admin')->where('is_active', true)->withCount('menus')->get();

        return view('welcome', compact('menus', 'sellers'));
    }
}
