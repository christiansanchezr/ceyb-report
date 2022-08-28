<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Traits\Date;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Spatie\Permission\Models\Role;

class UserCreateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function post(Request $request)
    {
        $validationRules = [
            'name' => 'required|string',
            'email' => 'required|unique:users',
            'password' => 'required|confirmed',
            'role' => 'required|exists:roles,id',
        ];

        $this->validate($request, $validationRules);

        $data = $request->only(['email', 'name']);
        $data['password'] = Hash::make($request->password);

        $user = User::create($data);
        $user->markEmailAsVerified();

        $role = Role::find($request->role);

        $user->assignRole([$role->name]);

        return redirect('users');
    }

    public function get() {
        $roles = Role::all();
        return view('users-create', ['roles' => $roles]);
    }
}
