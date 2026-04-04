<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Services;

class UserController extends Controller
{
    public function index()
    {
        return view('client.index');
    }

    public function services()
    {
        $services = Services::latest()->get();

        return view('client.services', compact('services'));
    }

    public function countries()
    {
        return view('client.countries');
    }

    public function jobs()
    {
        return view('client.jobs');
    }

    public function contact()
    {
        return view('client.contact');
    }
    public function assessment()
    {
        return view('client.assessment');
    }

    public function workVisa()
    {
        return view('client.work-visa');
    }

    public function drivers()
    {
        return view('client.drivers');
    }

    public function lightVehicleDriver()
    {
        return view('client.light-vehicle-driver');
    }

    public function ahmedVideos()
    {
        return view('client.ahmed-hassan-videos');
    }
    public function login(){

        return view('login');
    
    }

    public function auth_login(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Get credentials
        $credentials = $request->only('email', 'password');

        // Check if "Remember Me" is selected
        $remember = $request->boolean('remember');

        // Attempt login with remember option
        if (Auth::attempt($credentials, $remember)) {

            // Prevent session fixation
            $request->session()->regenerate();

            return redirect()->intended('dashboard')
                ->with('success', 'You are logged in successfully!');
        }

        // Authentication failed
        return back()
            ->withErrors([
                'email' => 'Invalid credentials provided.',
            ])
            ->withInput($request->except('password'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'You have been logged out.');
    }

    public function register(){

        return view('register');
    
    }
    public function auth_register(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'Guest'
        ]);

        return redirect()->route('login')->with('success', 'Registration successful. Please login.');

    }

}
