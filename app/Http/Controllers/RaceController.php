<?php

namespace App\Http\Controllers;

use App\Models\User;

class RaceController extends Controller
{
    public function index($name = null)
    {
        if (!is_null($name)) {
            $user = User::where('name', $name)->firstOrFail();
            return view('click', compact('user'));
        }

        $all_users = User::all();
        return view('welcome', compact('all_users'));

    }
}
