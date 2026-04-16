<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Services;
use App\Models\JobCategory;
use App\Models\JobSubcategory;
use App\Models\Passport;


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

    public function serviceCategories($serviceSlug)
    {
        $service = Services::with('categories')->where('slug', $serviceSlug)->firstOrFail();

        return view('client.servicetype', compact('service'));
    }

    public function categorySubcategories($serviceSlug,$categorySlug)
    {

        $category = JobCategory::with('subcategories', 'service')
            ->where('slug', $categorySlug)
            ->firstOrFail();

        $service = $category->service;

        return view('client.serviceposition', compact('category', 'service'));
    }
    public function subcategoryJobs($serviceSlug, $categorySlug, $subcategorySlug)
    {
        $subcategory = JobSubcategory::with(['passports', 'category.service'])
            ->where('slug', $subcategorySlug)
            ->whereHas('category', function($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            })
            ->whereHas('category.service', function($q) use ($serviceSlug) {
                $q->where('slug', $serviceSlug);
            })
            ->firstOrFail();

        return view('client.servicepositiontype', compact('subcategory'));
    }

    public function servicePosition($serviceSlug, $categorySlug, $subcategorySlug, $positionSlug = null)
    {
        $subcategory = JobSubcategory::with(['passports', 'category.service'])
            ->where('slug', $subcategorySlug)
            ->whereHas('category', function($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            })
            ->whereHas('category.service', function($q) use ($serviceSlug) {
                $q->where('slug', $serviceSlug);
            })
            ->firstOrFail();

        return view('client.servicepositiontype', compact('subcategory', 'positionSlug'));
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
        $service = Services::where('name', 'Work Visa')->firstOrFail();
        return view('client.work-visa', compact('service'));
    }

    public function drivers()
    {
        return view('client.drivers');
    }

    public function serviceworktype($slug = null)
    {
        if (!$slug) {
            return redirect()->route('work-visa');
        }

        $subcategory = JobSubcategory::with(['passports', 'category.service'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('client.servicepositiontype', compact('subcategory'));
    }

    public function ahmedVideos()
    {
        $passport = Passport::where('givenname', 'like', '%Ahmed%')
            ->where('familyname', 'like', '%Hassan%')
            ->with(['documents', 'videos'])
            ->first();

        return view('client.ahmed-hassan-videos', [
            'passport' => $passport,
            'documents' => $passport?->documents ?? collect(),
            'videos' => $passport?->videos ?? collect(),
        ]);
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
