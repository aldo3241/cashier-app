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
        try {
            $request->validate([
                'nama' => 'required|string|max:255|min:2',
                'username' => 'required|string|max:255|min:3|unique:akun,username|regex:/^[a-zA-Z0-9_]+$/',
                'email' => 'required|email|max:255|unique:akun,email',
                'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
                'role_id' => 'required|exists:roles,id',
            ], [
                'username.regex' => 'Username can only contain letters, numbers, and underscores.',
                'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number.',
                'nama.min' => 'Name must be at least 2 characters long.',
                'username.min' => 'Username must be at least 3 characters long.',
            ]);

            // Get the role to set both role_id and role fields
            $role = Role::find($request->role_id);
            
            if (!$role) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Selected role not found.');
            }

            $user = User::create([
                'nama' => trim($request->nama),
                'username' => trim($request->username),
                'email' => trim($request->email),
                'password' => bcrypt($request->password),
                'role_id' => $request->role_id,
                'role' => $role->name,
                'dibuat_oleh' => auth()->user()->nama,
                'date_created' => now(),
                'date_updated' => now(),
            ]);

            return redirect()->route('users.index')
                ->with('success', "User '{$user->nama}' created successfully!");

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create user: ' . $e->getMessage());
        }
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
        try {
            $request->validate([
                'nama' => 'required|string|max:255|min:2',
                'username' => 'required|string|max:255|min:3|unique:akun,username,' . $user->kd . '|regex:/^[a-zA-Z0-9_]+$/',
                'email' => 'required|email|max:255|unique:akun,email,' . $user->kd,
                'role_id' => 'required|exists:roles,id',
            ], [
                'username.regex' => 'Username can only contain letters, numbers, and underscores.',
                'nama.min' => 'Name must be at least 2 characters long.',
                'username.min' => 'Username must be at least 3 characters long.',
            ]);

            // Get the role to set both role_id and role fields
            $role = Role::find($request->role_id);
            
            if (!$role) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Selected role not found.');
            }

            $user->update([
                'nama' => trim($request->nama),
                'username' => trim($request->username),
                'email' => trim($request->email),
                'role_id' => $request->role_id,
                'role' => $role->name,
                'date_updated' => now(),
            ]);

            return redirect()->route('users.index')
                ->with('success', "User '{$user->nama}' updated successfully!");

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified user (Admin only)
     */
    public function destroy(User $user)
    {
        try {
            // Prevent admin from deleting themselves
            if ($user->kd === auth()->id()) {
                return redirect()->route('users.index')
                    ->with('error', 'You cannot delete your own account!');
            }

            // Check if user has any related data that might prevent deletion
            $userName = $user->nama;
            
            $user->delete();
            
            return redirect()->route('users.index')
                ->with('success', "User '{$userName}' deleted successfully!");

        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
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
        try {
            $isSelf = $user->kd === auth()->id();
            
            $request->validate([
                'current_password' => $isSelf ? 'required' : 'nullable',
                'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            ], [
                'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, and one number.',
            ]);

            // If changing own password, verify current password
            if ($isSelf && !password_verify($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }

            $user->update([
                'password' => bcrypt($request->password),
                'date_updated' => now()
            ]);

            $message = $isSelf ? 'Your password has been updated successfully.' : "Password updated for {$user->nama}.";
            
            return redirect()->route('users.show', $user)->with('success', $message);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update password: ' . $e->getMessage());
        }
    }
}