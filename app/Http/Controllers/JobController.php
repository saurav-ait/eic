<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\JobCategory;
use App\Models\JobSubcategory;
use App\Models\Passport;
use Illuminate\Validation\Rule;
use App\Models\Status;
use App\Models\Services;


class JobController extends Controller
{
    public function index()
    {
        $categories = JobCategory::with('subcategories.passports', 'service')->get();
        $passports = Passport::all();

        $subcategoryPassports = JobSubcategory::with('passports')->get()->map(function($sub){
            return [
                'id' => $sub->id,
                'name' => $sub->name,
                'passports' => $sub->passports->map(function($p){
                    return [
                        'id'              => $p->id,
                        'passport_number' => $p->passport_number,
                        'name'            => $p->familyname . ' ' . $p->givenname,
                    ];
                }),
            ];
        });

        return view('client.jobs.index', compact('categories', 'passports', 'subcategoryPassports'));
    }

    /* ================= CATEGORY STORE ================= */

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:job_categories,name',
            'service_id' => 'required|exists:services,id'
        ]);

        JobCategory::create([
            'name' => $request->name,
            'category_description' => $request->category_description,
            'service_id' => $request->service_id,
        ]);

        return back()->with('success', 'Category created successfully');
    }

    /* ================= CATEGORY UPDATE ================= */

    public function updateCategory(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:job_categories,name,' . $id,
            'service_id' => 'required|exists:services,id'
        ]);

        JobCategory::findOrFail($id)->update([
            'name' => $request->name,
            'category_description' => $request->category_description,
            'service_id' => $request->service_id,
        ]);

        return back()->with('success', 'Category updated');
    }

    public function deleteCategory($id)
    {
        JobCategory::findOrFail($id)->delete();
        return back()->with('success', 'Category deleted');
    }

    /* ================= SUBCATEGORY ================= */

    public function storeSubcategory(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                Rule::unique('job_subcategories')->where(function ($query) use ($request) {
                    return $query->where('job_category_id', $request->category_id);
                }),
            ],
            'category_id' => 'required|exists:job_categories,id'
        ]);

        JobSubcategory::create([
            'name' => $request->name,
            'job_category_id' => $request->category_id,
            'subcategory_description' => $request->subcategory_description,
        ]);

        return back()->with('success', 'Subcategory added successfully');
    }

    public function deleteSubcategory($id)
    {
        JobSubcategory::findOrFail($id)->delete();
        return back()->with('success', 'Subcategory deleted');
    }

    /* ================= PASSPORT ASSIGN ================= */

    public function assignPassport(Request $request)
    {
        $request->validate([
            'passport_id' => 'required|exists:passports,id',
            'subcategory_id' => 'required|exists:job_subcategories,id'
        ]);

        $passport = Passport::findOrFail($request->passport_id);
        $passport->job_subcategory_id = $request->subcategory_id;
        $passport->save();

        return back()->with('success', 'Passport assigned successfully');
    }

    public function unassignPassport($id)
    {
        $passport = Passport::findOrFail($id);

        $passport->job_subcategory_id = null;
        $passport->save();

        return back()->with('success', 'Passport unassigned successfully');
    }

    // Status
    public function statusIndex()
    {
        $statuses = Status::latest()->get();
        return view('client.jobs.status', compact('statuses'));
    }

    public function storeStatus(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:statuses,name',
            'description' => 'nullable'
        ]);

        Status::create($request->only('name', 'description'));

        return back()->with('success', 'Status added');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:statuses,name,' . $id
        ]);

        Status::findOrFail($id)->update($request->only('name','description'));

        return back()->with('success', 'Status updated');
    }

    public function deleteStatus($id)
    {
        Status::findOrFail($id)->delete();
        return back()->with('success', 'Status deleted');
    }
    
    // Passport Status
    public function passportStatus()
    {
        $passports = Passport::with('status')->get();
        $statuses = Status::all();

        return view('client.jobs.passport-status', compact('passports','statuses'));
    }

    public function updatePassportStatus(Request $request)
    {
        $request->validate([
            'passport_id' => 'required|exists:passports,id',
            'status_id' => 'required|exists:statuses,id'
        ]);

        $passport = Passport::findOrFail($request->passport_id);
        $passport->status_id = $request->status_id;
        $passport->save();

        return back()->with('success','Status updated');
    }

    // Services
    public function services()
    {
        $services = Services::with('categories')->latest()->paginate(10);
        return view('client.jobs.services', compact('services'));
    }
    public function storeService(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:services,name',
            'description' => 'nullable|string',
        ]);

        Services::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('jobs.services')
            ->with('success', 'Service created successfully.');
    }
    // Update service
    public function updateService(Request $request, Services $service)
    {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:services,name,' . $service->id,
            'description' => 'nullable|string',
        ]);

        // Update service
        $service->update($validated);

        return redirect()->route('jobs.services')
                         ->with('success', 'Service updated successfully');
    }

    public function destroyService(Services $service)
    {
        $service->delete();
        return redirect()->route('jobs.services')->with('success', 'Service deleted successfully.');
    }
}