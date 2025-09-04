<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of users (Admin only)
     */
    public function index()
    {
        $users = User::with('role')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user (Admin only)
     */
    public function create()
    {
        $roles = Role::active()->with('permissions')->get();
        return view('users.create', compact('roles'));
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
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id,
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
        $user->load('role.permissions');
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user (Admin only)
     */
    public function edit(User $user)
    {
        $roles = Role::active()->with('permissions')->get();
        $user->load('role.permissions');
        return view('users.edit', compact('user', 'roles'));
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
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->update([
            'nama' => $request->nama,
            'username' => $request->username,
            'email' => $request->email,
            'role_id' => $request->role_id,
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

    /**
     * Show the form for changing user password
     */
    public function changePassword(User $user)
    {
        return view('users.change-password', compact('user'));
    }

    /**
     * Update the user's password
     */
    public function updatePassword(Request $request, User $user)
    {
        $isSelf = $user->kd === auth()->id();
        
        $request->validate([
            'current_password' => $isSelf ? 'required' : 'nullable',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // If changing own password, verify current password
        if ($isSelf && !password_verify($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => bcrypt($request->password)
        ]);

        $message = $isSelf ? 'Your password has been updated successfully.' : "Password updated for {$user->nama}.";
        
        return redirect()->route('users.show', $user)->with('success', $message);
    }
}