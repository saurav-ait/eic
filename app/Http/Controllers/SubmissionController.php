<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\Passport;
use App\Models\Lawyer;
use App\Models\LawyerGroup;
use App\Models\Country;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Submission::with([
            'passport',
            'lawyer',
            'lawyerGroup',
            'country'
        ]);

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('submission_no', 'like', "%{$search}%")

                    ->orWhereHas('passport', function ($p) use ($search) {

                        $p->where('full_name', 'like', "%{$search}%")
                          ->orWhere('passport_number', 'like', "%{$search}%");

                    });

            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('country')) {
            $query->where('country_id', $request->country);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('submission_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('submission_date', '<=', $request->to_date);
        }

        $filteredQuery = clone $query;

        $statusCounts = $filteredQuery->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $total = $filteredQuery->count();
        $submitted = $statusCounts['Submitted'] ?? 0;
        $processing = $statusCounts['Processing'] ?? 0;
        $approved = $statusCounts['Approved'] ?? 0;
        $rejected = $statusCounts['Rejected'] ?? 0;
        $returned = $statusCounts['Returned'] ?? 0;
        $completed = $statusCounts['Completed'] ?? 0;

        $submissions = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $countries = Country::where('status',1)->get();

        return view(
            'client.submissions.index',
            compact(
                'submissions',
                'countries',
                'total',
                'submitted',
                'processing',
                'approved',
                'rejected',
                'returned',
                'completed'
            )
        );
    }

    public function create()
    {
        $passports = Passport::orderBy('passport_number')->get();

        $lawyers = Lawyer::orderBy('name')->get();

        $groups = LawyerGroup::orderBy('name')->get();

        $countries = Country::where('status',1)->get();

        $last = Submission::latest('id')->first();

                $next = $last ? $last->id + 1 : 1;

                $submissionNo =
                    'SUB-' .
                    date('Y') .
                    '-' .
                    str_pad($next,4,'0',STR_PAD_LEFT);

        return view(
            'client.submissions.create',
            compact(
                'passports',
                'lawyers',
                'groups',
                'countries',
                'submissionNo',
            )
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'passport_id' => 'required|exists:passports,id',
            'lawyer_id' => 'nullable|exists:lawyers,id',
            'lawyer_group_id' => 'nullable|exists:lawyer_groups,id',
            'country_id' => 'nullable|exists:countries,id',
            'submission_date' => 'required|date',
            'decision_date' => 'nullable|date',
            'submission_no' => 'nullable|string|max:255',
            'application_number' => 'nullable|string|max:255',
            'file_number' => 'nullable|string|max:255',
            'embassy_name' => 'nullable|string|max:255',
            'visa_type' => 'nullable|string|max:255',
            'status' => 'required|in:Draft,Submitted,Processing,Document Requested,Approved,Rejected,Returned,Completed',
            'remarks' => 'nullable|string',
        ]);

        if (empty($validated['submission_no'])) {
            $last = Submission::latest('id')->first();
            $next = $last ? $last->id + 1 : 1;
            $validated['submission_no'] = 'SUB-' . date('Y') . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);
        }

        Submission::create($validated);

        return redirect()
            ->route('submissions.index')
            ->with('success','Submission created');
    }

    public function edit(Submission $submission)
    {
        $passports = Passport::orderBy('passport_number')->get();

        $lawyers = Lawyer::orderBy('name')->get();

        $groups = LawyerGroup::orderBy('name')->get();

        $countries = Country::where('status',1)->get();

        return view(
            'client.submissions.edit',
            compact(
                'submission',
                'passports',
                'lawyers',
                'groups',
                'countries'
            )
        );
    }

    public function update(Request $request, Submission $submission)
    {
        $validated = $request->validate([
            'passport_id' => 'required|exists:passports,id',
            'lawyer_id' => 'nullable|exists:lawyers,id',
            'lawyer_group_id' => 'nullable|exists:lawyer_groups,id',
            'country_id' => 'nullable|exists:countries,id',
            'submission_date' => 'required|date',
            'decision_date' => 'nullable|date',
            'submission_no' => 'nullable|string|max:255',
            'application_number' => 'nullable|string|max:255',
            'file_number' => 'nullable|string|max:255',
            'embassy_name' => 'nullable|string|max:255',
            'visa_type' => 'nullable|string|max:255',
            'status' => 'required|in:Draft,Submitted,Processing,Document Requested,Approved,Rejected,Returned,Completed',
            'remarks' => 'nullable|string',
        ]);

        $submission->update($validated);

        return redirect()
            ->route('submissions.index')
            ->with('success','Submission updated');
    }

    public function destroy(Submission $submission)
    {
        $submission->delete();

        return back()->with(
            'success',
            'Submission deleted'
        );
    }
}