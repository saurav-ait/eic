<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use Carbon\Carbon;
use App\Models\Country;
use App\Models\ActivityType;
use App\Models\EmailTemplate;
use App\Models\TextTemplate;
use App\Models\LeadLog;
use App\Imports\LeadsImport;
use App\Exports\LeadsExport;
use Maatwebsite\Excel\Facades\Excel;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::with(['country','activity']);

        if ($request->filled('country')) {
            $query->where('country_id', $request->country);
        }

        if ($request->filled('activity')) {
            $query->where('activity_type_id', $request->activity);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('company_name','like',"%$s%")
                ->orWhere('phone','like',"%$s%")
                ->orWhere('email','like',"%$s%")
                ->orWhere('city','like',"%$s%");
            });
        }

        $countries = Country::all();
        $activities = ActivityType::all();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $leads = $query->latest()->paginate(10);

        $todayLeads = Lead::whereDate('created_at', Carbon::today())->count();
        $totalLeads = Lead::count();
        $monthlyLeads = Lead::whereMonth('created_at', Carbon::now()->month)->count();

        return view('client.leads.index', compact(
            'leads', 
            'countries', 
            'activities', 
            'todayLeads',
            'totalLeads',
            'monthlyLeads'
            ));

    }

    public function store(Request $request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'activity_type_id' => 'required|exists:activity_types,id',
            'company_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'director' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'status' => 'nullable|in:New,Contacted,Converted,Lost'
        ]);

        Lead::create($request->all());

        return back()->with('success','Lead added successfully');
    }

    public function update(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);
        
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'activity_type_id' => 'required|exists:activity_types,id',
            'company_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'director' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'status' => 'nullable|in:New,Contacted,Converted,Lost'
        ]);

        $lead->update($request->all());

        return back()->with('success','Lead updated successfully');
    }

    public function destroy($id)
    {
        Lead::findOrFail($id)->delete();

        return back()->with('success', 'Lead deleted successfully');
    }

    public function sendEmail($id)
    {
        $lead = Lead::findOrFail($id);
        $template = EmailTemplate::where('activity_type_id', $lead->activity_type_id)->first();

        if (!$template) {
            return back()->with('error', 'No email template found');
        }

        // Replace placeholders with actual lead data
        $subject = $this->replacePlaceholders($template->subject, $lead);
        $body = $this->replacePlaceholders($template->body, $lead);

        // TODO: Implement mail sending
        // Mail::to($lead->email)->send(new LeadEmail($subject, $body));
        
        LeadLog::create([
            'lead_id' => $lead->id,
            'type' => 'email',
            'content' => "Subject: {$subject}\n\n{$body}"
        ]);

        return back()->with('success','Email sent successfully');
    }

    public function sendText($id)
    {
        $lead = Lead::findOrFail($id);
        $template = TextTemplate::where('activity_type_id', $lead->activity_type_id)->first();

        if (!$template) {
            return back()->with('error', 'No text template found');
        }

        // Replace placeholders with actual lead data
        $message = $this->replacePlaceholders($template->body, $lead);

        // TODO: Integrate SMS/WhatsApp API
        // Twilio::message($lead->phone, $message);

        LeadLog::create([
            'lead_id' => $lead->id,
            'type' => 'text',
            'content' => $message
        ]);

        return back()->with('success','Message sent successfully');
    }

    /**
     * Replace placeholders in template with actual lead data
     */
    private function replacePlaceholders($text, $lead)
    {
        $placeholders = [
            '{company_name}' => $lead->company_name,
            '{director}' => $lead->director ?? '',
            '{phone}' => $lead->phone,
            '{email}' => $lead->email ?? '',
            '{city}' => $lead->city ?? '',
            '{address}' => $lead->address ?? '',
            '{country}' => $lead->country->name ?? '',
            '{activity_type}' => $lead->activity->name ?? '',
            '{status}' => $lead->status,
            '{date}' => now()->format('F d, Y'),
            '{time}' => now()->format('h:i A'),
        ];

        return str_replace(array_keys($placeholders), array_values($placeholders), $text);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ]);

        Excel::import(new LeadsImport, $request->file('file'));

        return back()->with('success','Leads imported successfully');
    }

    public function export()
    {
        return Excel::download(new LeadsExport, 'leads.xlsx');
    }
}