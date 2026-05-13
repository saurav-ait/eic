# Mailgun Email Setup Guide

## 📧 Overview
Your lead management system is now configured to send emails using Mailgun.

---

## ✅ What Was Configured

### 1. **Mailable Class Created**
- **File**: `app/Mail/LeadEmail.php`
- Handles email subject and body
- Uses dynamic content from templates

### 2. **Email View Created**
- **File**: `resources/views/emails/lead.blade.php`
- Professional HTML email template
- Responsive design
- Branded with your app name

### 3. **LeadController Updated**
- **File**: `app/Http/Controllers/LeadController.php`
- Integrated `Mail::to()->send()` with Mailgun
- Error handling for failed emails
- Email validation before sending
- Activity logging

### 4. **Configuration Files Updated**
- **config/mail.php** - Fixed mailgun transport
- **config/services.php** - Added Mailgun credentials

---

## 🔧 Environment Configuration

Add these variables to your `.env` file:

```env
# Mail Configuration
MAIL_MAILER=mailgun
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

# Mailgun Configuration
MAILGUN_DOMAIN=your-domain.mailgun.org
MAILGUN_SECRET=your-mailgun-api-key
MAILGUN_ENDPOINT=api.mailgun.net
```

### Getting Mailgun Credentials:

1. **Login to Mailgun**: https://app.mailgun.com/
2. **Get Domain**: 
   - Go to "Sending" → "Domains"
   - Copy your domain (e.g., `mg.yourdomain.com`)
3. **Get API Key**:
   - Go to "Settings" → "API Keys"
   - Copy your "Private API key"

### Example Configuration:
```env
MAIL_MAILER=mailgun
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Immigration Services"

MAILGUN_DOMAIN=mg.yourdomain.com
MAILGUN_SECRET=key-1234567890abcdef1234567890abcdef
MAILGUN_ENDPOINT=api.mailgun.net
```

**Note:** If you're using Mailgun EU region, use:
```env
MAILGUN_ENDPOINT=api.eu.mailgun.net
```

---

## 📦 Required Package

Make sure the Mailgun package is installed:

```bash
composer require symfony/mailgun-mailer symfony/http-client
```

If not installed, run the command above.

---

## 🧪 Testing Email Sending

### Test 1: Check Configuration
```bash
php artisan tinker
```

```php
>>> config('mail.default')
// Should return: "mailgun"

>>> config('services.mailgun.domain')
// Should return your domain

>>> config('mail.from.address')
// Should return your from address
```

### Test 2: Send Test Email
```bash
php artisan tinker
```

```php
use App\Mail\LeadEmail;
use Illuminate\Support\Facades\Mail;

Mail::to('test@example.com')->send(new LeadEmail('Test Subject', 'Test email body'));
```

### Test 3: Send from Lead Management
1. Go to `/admin/leads`
2. Find a lead with an email address
3. Click the 📧 button
4. Check if email was sent successfully

---

## 🎯 How It Works

### 1. **User Clicks Send Email Button**
```
Lead Page → 📧 Button → POST /admin/leads/{id}/send-email
```

### 2. **System Process**
```
1. Find lead by ID
2. Check if lead has email
3. Find email template for lead's activity type
4. Replace placeholders with lead data
5. Send email via Mailgun
6. Log activity in lead_logs table
7. Show success/error message
```

### 3. **Email Content**
- **Subject**: From template with placeholders replaced
- **Body**: From template with placeholders replaced
- **From**: Your configured MAIL_FROM_ADDRESS
- **To**: Lead's email address

### 4. **Placeholders Replaced**
```
{company_name}   → Lead's company name
{director}       → Lead's director name
{phone}          → Lead's phone
{email}          → Lead's email
{city}           → Lead's city
{country}        → Lead's country name
{activity_type}  → Lead's activity type
{status}         → Lead's status
{date}           → Current date
{time}           → Current time
```

---

## 📝 Email Template Example

### In Database (Email Template):
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

### Actual Email Sent:
**Subject:**
```
Welcome Tech Solutions Inc - Work Visa Application
```

**Body:**
```
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

---

## 🔍 Troubleshooting

### Issue: "Failed to send email: Connection refused"
**Solution:**
- Check your Mailgun credentials in `.env`
- Verify MAILGUN_DOMAIN and MAILGUN_SECRET are correct
- Run `php artisan config:clear`

### Issue: "Failed to send email: Domain not found"
**Solution:**
- Verify your domain in Mailgun dashboard
- Make sure domain is verified (check DNS records)
- Use the correct domain format (e.g., `mg.yourdomain.com`)

### Issue: "Failed to send email: Invalid API key"
**Solution:**
- Check MAILGUN_SECRET in `.env`
- Make sure you're using the Private API key, not Public
- Regenerate API key if needed

### Issue: "No email template found"
**Solution:**
- Create an email template for the lead's activity type
- Go to `/admin/email-templates`
- Add template for the specific activity type

### Issue: "Lead does not have an email address"
**Solution:**
- Add email address to the lead
- Edit the lead and add email field

### Issue: Emails going to spam
**Solution:**
- Verify your domain in Mailgun
- Set up SPF, DKIM, and DMARC records
- Use a custom domain instead of sandbox
- Add unsubscribe link to emails

---

## 📊 Email Logs

All sent emails are logged in the `lead_logs` table:

```sql
SELECT * FROM lead_logs WHERE type = 'email' ORDER BY created_at DESC;
```

View in application:
- Each email send is logged with:
  - Lead ID
  - Type: 'email'
  - Content: Subject and body
  - Timestamp

---

## 🚀 Production Checklist

Before going live:

- [ ] Mailgun account verified
- [ ] Domain verified in Mailgun
- [ ] DNS records configured (SPF, DKIM, DMARC)
- [ ] `.env` configured with correct credentials
- [ ] Test email sent successfully
- [ ] Email templates created for all activity types
- [ ] From address is professional (not noreply@sandbox...)
- [ ] Email design tested on multiple clients
- [ ] Unsubscribe mechanism implemented (if needed)
- [ ] Rate limits understood (Mailgun free tier: 5,000/month)

---

## 📈 Mailgun Dashboard

Monitor your emails:
- **Logs**: https://app.mailgun.com/app/logs
- **Analytics**: https://app.mailgun.com/app/analytics
- **Domains**: https://app.mailgun.com/app/sending/domains

---

## 💡 Best Practices

### 1. **Use Custom Domain**
Instead of sandbox domain, use your own:
```
mg.yourdomain.com
```

### 2. **Professional From Address**
```env
MAIL_FROM_ADDRESS=support@yourdomain.com
MAIL_FROM_NAME="Immigration Services"
```

### 3. **Test Before Production**
Always test with your own email first

### 4. **Monitor Deliverability**
Check Mailgun dashboard regularly for:
- Delivery rates
- Bounce rates
- Spam complaints

### 5. **Handle Failures Gracefully**
The system already has try-catch for error handling

---

## 🔐 Security Notes

1. **Never commit `.env` file** - Contains sensitive API keys
2. **Use environment variables** - Don't hardcode credentials
3. **Rotate API keys** - Change keys periodically
4. **Limit API key permissions** - Use sending-only keys if possible
5. **Monitor usage** - Watch for unusual activity

---

## 📞 Support

### Mailgun Support
- Documentation: https://documentation.mailgun.com/
- Support: https://help.mailgun.com/

### Laravel Mail Documentation
- https://laravel.com/docs/mail

---

## ✅ Quick Start

1. **Add credentials to `.env`:**
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.mailgun.org
MAILGUN_SECRET=your-api-key
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

2. **Clear config cache:**
```bash
php artisan config:clear
```

3. **Create email template:**
- Go to `/admin/email-templates`
- Add template for activity type

4. **Send test email:**
- Go to `/admin/leads`
- Click 📧 on any lead with email

---

**Status:** ✅ **READY TO SEND EMAILS**

Your system is now fully configured to send emails via Mailgun!
