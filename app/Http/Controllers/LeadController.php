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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\LeadEmail;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'country' => 'nullable|exists:countries,id',
            'activity' => 'nullable|exists:activity_types,id',
            'status' => 'nullable|in:New,Contacted,Email Sent,Converted,Lost',
            'search' => 'nullable|string|max:255'
        ]);

        $query = Lead::with(['country','activity']);

        if ($request->filled('country')) {
            $query->where('country_id', $request->country);
        }

        if ($request->filled('activity')) {
            $query->where('activity_type_id', $request->activity);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('company_name','like','%' . $request->search . '%')
                ->orWhere('phone','like','%' . $request->search . '%')
                ->orWhere('email','like','%' . $request->search . '%')
                ->orWhere('city','like','%' . $request->search . '%');
            });
        }

        $countries = Country::all();
        $activities = ActivityType::all();

        if ($request->filled('status')) {
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
            'status' => 'nullable|in:New,Contacted,Email Sent,Converted,Lost'
        ]);

        Lead::create($request->all());

        return back()->with('success','Lead added successfully');
    }

    public function update(Request $request, int $id)
    {
        $validated = validator(['id' => $id], [
            'id' => 'required|integer|exists:leads,id'
        ])->validate();

        $lead = Lead::findOrFail($validated['id']);
        
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'activity_type_id' => 'required|exists:activity_types,id',
            'company_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'director' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'status' => 'nullable|in:New,Contacted,Email Sent,Converted,Lost'
        ]);

        $lead->update($request->all());

        return back()->with('success','Lead updated successfully');
    }

    public function destroy(int $id)
    {
        $validated = validator(['id' => $id], [
            'id' => 'required|integer|exists:leads,id'
        ])->validate();

        Lead::findOrFail($validated['id'])->delete();

        return back()->with('success', 'Lead deleted successfully');
    }

    public function sendEmail(int $id)
    {
        $validated = validator(['id' => $id], [
            'id' => 'required|integer|exists:leads,id'
        ])->validate();

        Log::info('SendEmail called for lead ID: ' . $validated['id']);
        $lead = Lead::with(['activityType', 'country'])->findOrFail($validated['id']);
        
        if (!$lead->email) {
            Log::warning('Lead has no email: ' . $validated['id']);
            return back()->with('error', 'Lead does not have an email address');
        }

        if (!$lead->activityType) {
            Log::warning('Lead has no activity type: ' . $validated['id']);
            return back()->with('error', 'Lead does not have an activity type assigned');
        }
        
        $template = $lead->activityType->emailTemplates()->first();

        if (!$template) {
            Log::warning('No template found for activity type: ' . $lead->activity_type_id);
            return back()->with('error', 'No email template found for this activity type');
        }

        // Replace placeholders with actual lead data
        $subject = $this->replacePlaceholders($template->subject, $lead);
        $body = $this->replacePlaceholders($template->body, $lead);

        // Format the body for HTML email
        $body = $this->formatEmailBody($body);

        Log::info('Attempting to send email to: ' . $lead->email);

        try {
            // Send email using Mailgun
            Mail::to($lead->email)->send(new LeadEmail($subject, $body));
            
            Log::info('Email sent successfully to: ' . $lead->email);
            
            // Update lead status to 'Email Sent'
            $lead->update(['status' => 'Email Sent']);
            
            // Log the activity
            LeadLog::create([
                'lead_id' => $lead->id,
                'type' => 'email',
                'content' => "Subject: {$subject}\n\n{$body}"
            ]);

            return back()->with('success', 'Email sent successfully to ' . $lead->email . ' and status updated');
        } catch (\Exception $e) {
            Log::error('Email send failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    public function sendText(int $id)
    {
        $validated = validator(['id' => $id], [
            'id' => 'required|integer|exists:leads,id'
        ])->validate();

        $lead = Lead::with(['activityType', 'country'])->findOrFail($validated['id']);

        if (!$lead->activityType) {
            return back()->with('error', 'Lead does not have an activity type assigned');
        }

        $template = $lead->activityType->textTemplates()->first();

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
    private function replacePlaceholders(string $text, Lead $lead)
    {
        $placeholders = [
            '{company_name}' => $lead->company_name,
            '{director}' => $lead->director ?? '',
            '{phone}' => $lead->phone,
            '{email}' => $lead->email ?? '',
            '{city}' => $lead->city ?? '',
            '{address}' => $lead->address ?? '',
            '{country}' => $lead->country->name ?? '',
            '{activity_type}' => $lead->activityType->name ?? '',
            '{status}' => $lead->status,
            '{date}' => now()->format('F d, Y'),
            '{time}' => now()->format('h:i A'),
            '{b}' => '<b>',
            '{/b}' => '</b>',
        ];

        return str_replace(array_keys($placeholders), array_values($placeholders), $text);
    }

    /**
     * Format the email body with proper HTML paragraphs
     */
    private function formatEmailBody(string $body)
    {
        // Replace double newlines with paragraph breaks
        $body = preg_replace('/\n\s*\n/', '</p><p>', $body);
        // Wrap in p tags
        $body = '<p>' . $body . '</p>';
        // Replace single newlines with <br>
        $body = str_replace("\n", '<br>', $body);
        return $body;
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