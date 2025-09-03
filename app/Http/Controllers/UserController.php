<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of users (Admin only)
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user (Admin only)
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user (Admin only)
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:akun,username',
            'email' => 'required|email|max:255|unique:akun,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,cashier',
        ]);

        User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'dibuat_oleh' => auth()->user()->nama,
            'date_created' => now(),
            'date_updated' => now(),
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    /**
     * Display the specified user (Admin only)
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user (Admin only)
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user (Admin only)
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:akun,username,' . $user->kd,
            'email' => 'required|email|max:255|unique:akun,email,' . $user->kd,
            'role' => 'required|in:admin,cashier',
        ]);

        $user->update([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
            'date_updated' => now(),
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user (Admin only)
     */
    public function destroy(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->kd === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account!');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
}