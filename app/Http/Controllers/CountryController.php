<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Country;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        $query = Country::query();

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('status', 'like', "%{$request->search}%");
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $countries = $query->latest()->paginate(10);

        $todayCountries = Country::whereDate('created_at', Carbon::today())->count();
        $totalCountries = Country::count();
        $monthlyCountries = Country::whereMonth('created_at', Carbon::now()->month)->count();

        return view('client.country.index', compact(
            'countries',
            'todayCountries',
            'totalCountries',
            'monthlyCountries'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'required|string|max:255',
            'status' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['status'] = $request->boolean('status', false);

        Country::create($data);

        return back()->with('success', 'Country added successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'details' => 'required|string|max:255',
            'status' => 'nullable|boolean',
        ]);

        $country = Country::findOrFail($id);

        $data = $request->all();
        $data['status'] = $request->boolean('status', false);

        $country->update($data);

        return back()->with('success', 'Country updated');
    }

    public function destroy($id)
    {
        Country::findOrFail($id)->delete();

        return back()->with('success', 'Country deleted');
    }
}