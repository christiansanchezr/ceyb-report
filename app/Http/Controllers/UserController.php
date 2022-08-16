<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Traits\Date;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function search(Request $request)
    {
        $validationRules = [
            'value' => 'string'
        ];

        $this->validate($request, $validationRules);

        if ($request->value) {
            $users = User::where('id', $request->value)->orWhere('email', 'like', '%'.$request->value.'%')->orWhere('name', 'like', '%'.$request->value.'%')->get();
        } else {
            $users = User::all();
        }
        return view('users', ['users' => $users]);

    }

    public function get() {
        $users = User::all();
        return view('users', ['users' => $users]);
    }
}
