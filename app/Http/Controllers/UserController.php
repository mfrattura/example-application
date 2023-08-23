<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    public function index(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $users = User::query()->latest()->paginate(10);

        return view('user.index', compact('users'));
    }

    public function create(): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('user.create');
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);
        $fileName =time() . '.' . $request->image->extension();
        $request->image->storeAs('public/images', $fileName);

        $user = new User;
        $user->name = $request->input('name');
        $user->email = trim($request->input('email'));
        $user->password = bcrypt($request->input('password'));
        $user->image = $fileName;
        $user->save();

        return redirect()->route('user.index')->with([
           'message' => 'User added successfully!',
           'status' => 'success'
        ]);
    }
}
