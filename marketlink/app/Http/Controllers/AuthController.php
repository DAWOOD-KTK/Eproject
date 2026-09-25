<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Market;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->isSuspended()) {
                Auth::logout();
                return back()->with('error', 'Your account has been suspended by the administrator. Please contact support.');
            }

            return $this->redirectBasedOnRole($user)->with('success', 'Welcome back, ' . $user->name . '!');
        }

        return back()->withInput($request->only('email', 'remember'))->with('error', 'Invalid email address or password.');
    }

    public function showRegister(Request $request)
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        $role = $request->query('role', 'customer');
        $markets = Market::orderBy('market_name')->get();
        return view('auth.register', compact('role', 'markets'));
    }

    public function register(Request $request)
    {
        $role = $request->input('role', 'customer');

        if ($role === 'farmer') {
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'contact_person' => 'required|string|max:100',
                'stall_name' => 'required|string|max:150',
                'email' => 'required|email|max:150|unique:users',
                'password' => 'required|min:6|confirmed',
                'phone' => 'required|string|max:30',
                'address' => 'required|string',
                'market_id' => 'nullable|exists:markets,id',
                'operating_days' => 'nullable|string',
                'pickup_windows' => 'nullable|string',
                'cutoff_hours' => 'nullable|integer|min:1|max:72',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'bio' => 'nullable|string|max:1000',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'contact_person' => $validated['contact_person'],
                'stall_name' => $validated['stall_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'farmer',
                'status' => 'active', // Set active so they can immediately test and list products
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'market_id' => $validated['market_id'] ?? null,
                'operating_days' => $validated['operating_days'] ?? 'Saturday, Sunday',
                'pickup_windows' => $validated['pickup_windows'] ?? '08:00 AM - 01:00 PM',
                'cutoff_hours' => $validated['cutoff_hours'] ?? 12,
                'latitude' => $validated['latitude'] ?? 24.8607,
                'longitude' => $validated['longitude'] ?? 67.0011,
                'bio' => $validated['bio'] ?? null,
            ]);

            Auth::login($user);
            return redirect()->route('farmer.dashboard')->with('success', 'Farmer registration successful! Welcome to your Farmer Stall portal.');
        } else {
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'required|email|max:150|unique:users',
                'password' => 'required|min:6|confirmed',
                'phone' => 'required|string|max:30',
                'address' => 'required|string',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'customer',
                'status' => 'active',
                'phone' => $validated['phone'],
                'address' => $validated['address'],
            ]);

            Auth::login($user);
            return redirect()->route('home')->with('success', 'Account created successfully! Welcome to MarketLink.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('info', 'You have been successfully logged out.');
    }

    private function redirectBasedOnRole($user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isFarmer()) {
            return redirect()->route('farmer.dashboard');
        } else {
            return redirect()->route('customer.dashboard');
        }
    }
}
