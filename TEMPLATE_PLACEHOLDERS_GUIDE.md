# Email & Text Template Placeholders Guide

## 📧 Overview

The Lead Management System supports **dynamic placeholders** in email and text templates. These placeholders are automatically replaced with actual lead data when sending emails or SMS/WhatsApp messages.

---

## 🏷️ Available Placeholders

| Placeholder | Description | Example Output |
|-------------|-------------|----------------|
| `{company_name}` | Company name | "Tech Solutions Inc" |
| `{director}` | Director/Contact person name | "John Smith" |
| `{phone}` | Phone number | "+1234567890" |
| `{email}` | Email address | "john@techsolutions.com" |
| `{city}` | City name | "New York" |
| `{address}` | Full address | "123 Main Street" |
| `{country}` | Country name | "United States" |
| `{activity_type}` | Activity type name | "Work Visa" |
| `{status}` | Lead status | "New" / "Contacted" / "Converted" / "Lost" |
| `{date}` | Current date | "January 15, 2024" |
| `{time}` | Current time | "02:30 PM" |

---

## 📝 Email Template Examples

### Example 1: Welcome Email
**Subject:**
```
Welcome {company_name} - {activity_type} Application
```

**Body:**
```
Dear {director},

Thank you for your interest in our {activity_type} services!

We have received your inquiry from {company_name} located in {city}, {country}.

Our team will review your application and contact you at {email} or {phone} within 24 hours.

Application Details:
- Company: {company_name}
- Contact Person: {director}
- Location: {city}, {country}
- Service: {activity_type}
- Status: {status}
- Date: {date}

Best regards,
Immigration Services Team
```

**Output (when sent):**
```
Subject: Welcome Tech Solutions Inc - Work Visa Application

Dear John Smith,

Thank you for your interest in our Work Visa services!

We have received your inquiry from Tech Solutions Inc located in New York, United States.

Our team will review your application and contact you at john@techsolutions.com or +1234567890 within 24 hours.

Application Details:
- Company: Tech Solutions Inc
- Contact Person: John Smith
- Location: New York, United States
- Service: Work Visa
- Status: New
- Date: January 15, 2024

Best regards,
Immigration Services Team
```

### Example 2: Follow-up Email
**Subject:**
```
Follow-up: {activity_type} for {company_name}
```

**Body:**
```
Hello {director},

This is a follow-up regarding your {activity_type} application submitted on {date}.

Current Status: {status}

If you have any questions, please contact us at your convenience.

Company: {company_name}
Location: {city}, {country}
Contact: {phone}

Thank you!
```

### Example 3: Status Update Email
**Subject:**
```
Status Update: Your {activity_type} Application
```

**Body:**
```
Dear {director},

Your {activity_type} application for {company_name} has been updated.

New Status: {status}
Updated: {date} at {time}

For more information, please contact us.

Best regards
```

---

## 💬 Text/SMS Template Examples

### Example 1: Welcome SMS
```
Hi {director}! Thanks for contacting us about {activity_type}. We'll review your application from {company_name} and get back to you soon. - Immigration Services
```

**Output:**
```
Hi John Smith! Thanks for contacting us about Work Visa. We'll review your application from Tech Solutions Inc and get back to you soon. - Immigration Services
```

### Example 2: Reminder SMS
```
Hello {director}, this is a reminder about your {activity_type} application. Status: {status}. Contact us if you need assistance. Call: +1234567890
```

### Example 3: Confirmation SMS
```
{company_name} - Your {activity_type} application is confirmed! Status: {status}. We'll contact you at {email}. Thank you!
```

### Example 4: Follow-up WhatsApp
```
Hi {director} from {company_name}! 

Your {activity_type} application status: {status}

Location: {city}, {country}
Date: {date}

Need help? Reply to this message!
```

---

## 🎯 How to Use

### Creating Email Template

1. Go to `/admin/email-templates`
2. Click "+ Add Template"
3. Select Activity Type
4. Enter Subject with placeholders:
   ```
   Welcome {company_name} - {activity_type}
   ```
5. Enter Body with placeholders:
   ```
   Dear {director},
   
   Thank you for contacting us about {activity_type}.
   We received your inquiry from {city}, {country}.
   
   Best regards
   ```
6. Click "Save"

### Creating Text Template

1. Go to `/admin/text-templates`
2. Click "+ Add Template"
3. Select Activity Type
4. Enter Message with placeholders:
   ```
   Hi {director}! Your {activity_type} application for {company_name} is being processed. Status: {status}. Thanks!
   ```
5. Click "Save"

### Sending Messages

#### From Leads Page:
1. Go to `/admin/leads`
2. Find the lead you want to contact
3. Click the 📧 (email) button to send email
4. Click the 💬 (message) button to send SMS/WhatsApp

The system will:
- Find the template for that lead's activity type
- Replace all placeholders with actual lead data
- Send the personalized message
- Log the activity in lead logs

---

## ⚙️ Technical Details

### Placeholder Replacement Function

The system uses the `replacePlaceholders()` method in `LeadController`:

```php
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
```

### Empty Values
- If a placeholder value is empty (e.g., director not provided), it will be replaced with an empty string
- Use conditional text in templates if needed

---

## 💡 Best Practices

### 1. Always Test Templates
Create a test lead and send test messages before using templates with real leads.

### 2. Keep SMS Short
SMS templates should be under 160 characters for single SMS. Use placeholders wisely.

### 3. Professional Tone
Use professional language in templates as they represent your business.

### 4. Include Contact Info
Always include a way for leads to contact you back.

### 5. Personalization
Use `{director}` or `{company_name}` at the beginning to personalize messages.

### 6. Clear Call-to-Action
Tell leads what to do next (reply, call, visit website, etc.).

---

## 🔄 Template Management

### One Template Per Activity Type
- Each activity type can have ONE email template
- Each activity type can have ONE text template
- If you create a new template for an existing activity type, update the old one instead

### Updating Templates
1. Go to templates page
2. Click "Edit" on the template
3. Modify placeholders and text
4. Click "Save"

### Deleting Templates
- You can delete templates if they're no longer needed
- Leads can still be managed without templates
- You just won't be able to send automated messages for that activity type

---

## 📊 Activity Logs

Every email and SMS sent is logged in the `lead_logs` table with:
- Lead ID
- Type (email or text)
- Content (with placeholders replaced)
- Timestamp

You can view these logs to track all communications with each lead.

---

## 🚀 Future Enhancements

### Planned Features:
1. **Multiple Templates** - Multiple templates per activity type
2. **Template Variables** - Custom variables beyond standard placeholders
3. **Conditional Content** - Show/hide sections based on lead data
4. **Attachments** - Attach PDFs to emails
5. **Scheduled Sending** - Schedule messages for later
6. **A/B Testing** - Test different template versions
7. **Analytics** - Track open rates, click rates

---

## 📞 Example Use Cases

### Use Case 1: Work Visa Application
**Email Template:**
```
Subject: Work Visa Application - {company_name}

Dear {director},

Thank you for choosing our Work Visa services for {company_name}.

We have received your application for {country} work visa.

Next Steps:
1. Document verification
2. Application processing
3. Interview scheduling

We'll contact you at {email} or {phone} with updates.

Location: {city}, {country}
Status: {status}
Date: {date}

Best regards,
Visa Processing Team
```

### Use Case 2: Study Visa Follow-up
**SMS Template:**
```
Hi {director}! Your {activity_type} application for {country} is {status}. Need documents? Call us or check your email at {email}. Thanks!
```

### Use Case 3: Status Update
**Email Template:**
```
Subject: Status Update - {activity_type}

Hello {director},

Your {activity_type} application status has been updated to: {status}

Company: {company_name}
Updated: {date} at {time}

For questions, contact us anytime.

Thank you!
```

---

## ✅ Checklist for Creating Templates

- [ ] Choose appropriate activity type
- [ ] Write clear, professional subject line (email only)
- [ ] Use relevant placeholders
- [ ] Include company/director name for personalization
- [ ] Add contact information
- [ ] Keep SMS under 160 characters
- [ ] Test with sample lead
- [ ] Verify all placeholders work
- [ ] Check grammar and spelling
- [ ] Save template

---

**Documentation Version**: 1.0  
**Last Updated**: 2024  
**Related Files**: 
- `app/Http/Controllers/LeadController.php`
- `resources/views/client/email-templates/index.blade.php`
- `resources/views/client/text-templates/index.blade.php`
