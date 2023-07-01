<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

final class AdminUserController extends Controller{

    function users(Request $request) {

        $list = User::paginate(10);

        return view('admin.user.list', [
            'title' => 'Users',
            'list' => $list,
        ]);
    }

    function newUser() {

        return view('admin.user.new', [
            'title' => 'User',
        ]);
    }
    function createUser(Request $request) {

        /* 
        Validation
        */
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5',
        ]);

        /*
        Database Insert
        */
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return view('admin.user.new', [
            'title' => 'User',
        ])->with('success', 'User created successfully.');
    }

    // users
    // user
    // editUser
    // updateUser
    // deleteUser

}