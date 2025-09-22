<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\Registered;

class UnifiedAuthController extends Controller
{
    /**
     * Show unified login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Handle unified login - checks both customers and users tables
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');
        $remember = $request->filled('remember');

        // First, try to authenticate as customer
        $customer = Customer::where('email', $email)->first();
        
        if ($customer && Hash::check($password, $customer->password)) {
            // Log in as customer
            Auth::guard('customer')->login($customer, $remember);
            $request->session()->regenerate();
            
            return redirect()->intended(route('home'))
                ->with('success', 'Welcome back! You can now proceed with your purchase.');
        }

        // Second, try to authenticate as admin user
        $user = User::where('email', $email)->first();
        
        if ($user && Hash::check($password, $user->password)) {
            // Check if user has admin role
            if (in_array($user->role, ['admin', 'super_admin'])) {
                // Log in as admin
                Auth::guard('web')->login($user, $remember);
                $request->session()->regenerate();
                
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', 'Welcome back, ' . ucfirst($user->role) . '!');
            } else {
                // User exists but doesn't have admin role
                return back()->withErrors([
                    'email' => 'You do not have permission to access the admin panel.',
                ])->onlyInput('email');
            }
        }

        // Neither customer nor admin found
        throw ValidationException::withMessages([
            'email' => 'These credentials do not match our records.',
        ]);
    }

    /**
     * Show customer registration form
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle customer registration (normal users register as customers)
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers,email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        // Create customer (not admin user)
        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        event(new Registered($customer));
        
        // Log in the new customer
        Auth::guard('customer')->login($customer);

        return redirect()->route('home')
            ->with('success', 'Account created successfully!');
    }

    /**
     * Handle logout for both customers and admins
     */
    public function logout(Request $request)
    {
        // Check which guard is currently authenticated
        if (Auth::guard('customer')->check()) {
            Auth::guard('customer')->logout();
            $redirectRoute = 'home';
            $message = 'You have been logged out successfully.';
        } elseif (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
            $redirectRoute = 'home';
            $message = 'Admin logout successful.';
        } else {
            $redirectRoute = 'home';
            $message = 'You have been logged out.';
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route($redirectRoute)->with('success', $message);
    }

    /**
     * Show admin registration form (only for creating admin accounts)
     */
    public function showAdminRegister()
    {
        // Only allow this if no admin users exist, or if current user is super_admin
        if (User::where('role', 'admin')->orWhere('role', 'super_admin')->exists()) {
            if (!Auth::check() || !in_array(Auth::user()->role, ['super_admin'])) {
                abort(403, 'Unauthorized to create admin accounts.');
            }
        }

        return view('auth.admin-register');
    }

    /**
     * Create admin user (restricted access)
     */
    public function createAdmin(Request $request)
    {
        // Only allow if no admin exists, or current user is super_admin
        if (User::where('role', 'admin')->orWhere('role', 'super_admin')->exists()) {
            if (!Auth::check() || !in_array(Auth::user()->role, ['super_admin'])) {
                abort(403, 'Unauthorized to create admin accounts.');
            }
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email|unique:customers,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,super_admin',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        event(new Registered($user));

        return redirect()->route('admin.dashboard')
            ->with('success', ucfirst($request->role) . ' account created successfully!');
    }
}
