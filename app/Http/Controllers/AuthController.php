<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username harus diisi.',
            'password.required' => 'Password harus diisi.',
        ]);

        // Rate limiting for login attempts
        $key = Str::lower($request->input('username')) . '|' . $request->ip();
        
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            
            throw ValidationException::withMessages([
                'username' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
            ]);
        }

        // Attempt to authenticate the user
        $credentials = $request->only('username', 'password');
        
        // Find user by username or email
        $user = User::where('username', $credentials['username'])
                   ->orWhere('email', $credentials['username'])
                   ->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            RateLimiter::hit($key, 300); // 5 minutes lockout
            
            throw ValidationException::withMessages([
                'username' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
            ]);
        }

        // Check if user account is active (you can add status field to your akun table)
        // if ($user->status !== 'active') {
        //     throw ValidationException::withMessages([
        //         'username' => 'Akun Anda tidak aktif. Hubungi administrator.',
        //     ]);
        // }

        // Clear rate limiting on successful login
        RateLimiter::clear($key);

        // Log the user in
        Auth::login($user, $request->boolean('remember'));

        // Regenerate session ID for security
        $request->session()->regenerate();

        // Log successful login
        \Log::info('User login successful', [
            'user_id' => $user->kd,
            'username' => $user->username,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Log logout
        if ($user) {
            \Log::info('User logout', [
                'user_id' => $user->kd,
                'username' => $user->username,
                'ip' => $request->ip(),
            ]);
        }

        return redirect()->route('login');
    }

    /**
     * Show the user profile.
     */
    public function profile()
    {
        $user = Auth::user();
        return view('auth.profile', compact('user'));
    }

    /**
     * Update user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:akun,email,' . $user->kd . ',kd',
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'nama.required' => 'Nama harus diisi.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'photo_profile.image' => 'File harus berupa gambar.',
            'photo_profile.mimes' => 'Gambar harus berformat jpeg, png, jpg, atau gif.',
            'photo_profile.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $data = $request->only(['nama', 'email']);
        
        if ($request->hasFile('photo_profile')) {
            $file = $request->file('photo_profile');
            $filename = time() . '_' . $user->kd . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/profiles', $filename);
            $data['photo_profile'] = 'profiles/' . $filename;
        }

        $user->update($data);

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Show change password form.
     */
    public function showChangePasswordForm()
    {
        return view('auth.change-password');
    }

    /**
     * Change user password.
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Password lama harus diisi.',
            'password.required' => 'Password baru harus diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak cocok.']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        \Log::info('User changed password', [
            'user_id' => $user->kd,
            'username' => $user->username,
            'ip' => $request->ip(),
        ]);

        return redirect()->route('profile')->with('success', 'Password berhasil diubah.');
    }
}
