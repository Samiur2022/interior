<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show admin login form.
     */
    public function showLogin()
    {
        return view('backend.auth.login');
    }


    /**
     * Process admin login.
     */
    public function login(Request $request)
    {
        // Validate login form
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);


        // Find user by email
        $user = User::where('email', $credentials['email'])->first();


        // Check user exists and password is correct
        if (!$user || !Hash::check($credentials['password'], $user->password)) {

            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Invalid email or password.',
                ]);
        }


        // Regenerate session ID after successful login
        $request->session()->regenerate();


        // Store logged-in user ID in session
        session([
            'admin_user_id' => $user->id,
        ]);


        // Redirect to admin dashboard
        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Welcome back!');
    }


    /**
     * Logout admin user.
     */
    public function logout(Request $request)
    {
        // Remove admin session
        $request->session()->forget('admin_user_id');


        // Destroy current session
        $request->session()->invalidate();


        // Generate new CSRF token
        $request->session()->regenerateToken();


        // Return to login page
        return redirect()
            ->route('login')
            ->with('success', 'You have been logged out.');
    }
}