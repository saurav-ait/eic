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
        $categories = JobCategory::with('subcategories.passports')->get();
        $passports = \App\Models\Passport::all();

        $subcategoryPassports = JobSubcategory::with('passports')->get()->map(function($sub){
            return [
                'id' => $sub->id,
                'name' => $sub->name,
                'passports' => $sub->passports->map(function($p){
                    return [
                        'id' => $p->id,
                        'passport_no' => $p->passport_no,
                        'name' => $p->name,
                    ];
                }),
            ];
        });

        return view('client.jobs.index', compact('categories', 'passports', 'subcategoryPassports'));
    }

    /* ================= CATEGORY ================= */

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:job_categories,name'
        ]);

        JobCategory::create([
            'name' => $request->name,
            'category_description' => $request->category_description,
        ]);

        return back()->with('success', 'Category created successfully');
    }

    /* ================= CATEGORY ================= */

    public function updateCategory(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:job_categories,name,' . $id
        ]);

        JobCategory::findOrFail($id)->update([
            'name' => $request->name,
            'category_description' => $request->category_description,
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

        \App\Models\JobSubcategory::create([
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
        $services = Services::with('categories', 'subcategories')->get();
        return view('client.jobs.services', compact('services'));
    }
    public function destroyService(Services $service)
    {
        if ($service->categories()->exists() || $service->subcategories()->exists()) {
            return redirect()->route('services.index')->with('error', 'Cannot delete service with assigned categories or subcategories.');
        }

        $service->delete();
        return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
    }
}