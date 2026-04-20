<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Vendor;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $query = Vendor::query();

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('type', 'like', "%{$request->search}%")
                  ->orWhere('status', 'like', "%{$request->search}%");
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->status !== null) {
            $query->where('status', $request->status);
        }

        $vendors = $query->latest()->paginate(10);

        $todayVendors = Vendor::whereDate('created_at', Carbon::today())->count();
        $totalVendors = Vendor::count();
        $monthlyVendors = Vendor::whereMonth('created_at', Carbon::now()->month)->count();

        return view('client.vendor.index', compact(
            'vendors',
            'todayVendors',
            'totalVendors',
            'monthlyVendors'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Client,Agent,Others',
            'details' => 'nullable|string|max:1000',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'status' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['status'] = $request->boolean('status', true);

        Vendor::create($data);

        return back()->with('success', 'Vendor added successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Client,Agent,Others',
            'details' => 'nullable|string|max:1000',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'status' => 'nullable|boolean',
        ]);

        $vendor = Vendor::findOrFail($id);

        $data = $request->all();
        $data['status'] = $request->boolean('status', true);

        $vendor->update($data);

        return back()->with('success', 'Vendor updated');
    }

    public function destroy($id)
    {
        Vendor::findOrFail($id)->delete();

        return back()->with('success', 'Vendor deleted');
    }
}
