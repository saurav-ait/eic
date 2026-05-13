# Email Sending 302 Redirect - Troubleshooting Guide

## 🔍 Understanding the 302 Redirect

A **302 redirect** when clicking the email button is **NORMAL** behavior in Laravel. It means:
- The form submitted successfully
- Laravel processed the request
- Laravel redirected back to the previous page using `return back()`

The 302 is **NOT an error** - it's how Laravel handles form submissions.

---

## ✅ How to Check if Email Sent Successfully

### Method 1: Check Success/Error Messages

After clicking the 📧 button, look at the **top of the page** for:

**Success Message (Green):**
```
Email sent successfully to john@example.com and status updated
```

**Error Message (Red):**
```
Failed to send email: [error details]
```
or
```
Lead does not have an email address
```
or
```
No email template found for this activity type
```

### Method 2: Check Lead Status

1. After clicking 📧, check the lead's status column
2. If email sent successfully, status should change to **"Email Sent"**
3. If status didn't change, email failed

### Method 3: Check Laravel Logs

```bash
# View recent logs
php artisan tail

# Or manually check
notepad storage\logs\laravel.log
```

Look for:
```
SendEmail called for lead ID: X
Attempting to send email to: email@example.com
Email sent successfully to: email@example.com
```

Or errors:
```
Email send failed: [error message]
```

---

## 🧪 Test Email Configuration

Run this command to test your Mailgun setup:

```bash
php artisan test:email your-email@example.com
```

This will:
- Show your current configuration
- Attempt to send a test email
- Display success or error message

**Expected Output (Success):**
```
Testing email configuration...
Mail Driver: mailgun
Mailgun Domain: mg.yourdomain.com
From Address: noreply@yourdomain.com

Sending test email to: test@example.com
✅ Email sent successfully!
```

**Expected Output (Failure):**
```
Testing email configuration...
Mail Driver: mailgun
Mailgun Domain: mg.yourdomain.com
From Address: noreply@yourdomain.com

Sending test email to: test@example.com
❌ Failed to send email:
[Error details here]
```

---

## 🔧 Common Issues & Solutions

### Issue 1: No Error Message Displayed

**Problem:** 302 redirect but no success/error message shown

**Solution:**
1. Check if error messages are displayed in the view
2. Clear browser cache (Ctrl+Shift+R)
3. Check session is working:
```bash
php artisan config:clear
```

### Issue 2: "No email template found"

**Problem:** Email template doesn't exist for the lead's activity type

**Solution:**
1. Go to `/admin/email-templates`
2. Create a template for the lead's activity type
3. Try sending email again

### Issue 3: "Lead does not have an email address"

**Problem:** The lead has no email in the database

**Solution:**
1. Edit the lead
2. Add an email address
3. Try sending email again

### Issue 4: Mailgun Authentication Error

**Problem:** Invalid API key or domain

**Solution:**
1. Check `.env` file:
```env
MAILGUN_DOMAIN=your-domain.mailgun.org
MAILGUN_SECRET=key-your-api-key
```
2. Verify credentials at https://app.mailgun.com/
3. Clear config cache:
```bash
php artisan config:clear
```

### Issue 5: Email Sends But Not Received

**Problem:** Email sent successfully but not in inbox

**Solution:**
1. Check spam folder
2. Verify domain in Mailgun dashboard
3. Check Mailgun logs: https://app.mailgun.com/app/logs
4. Verify DNS records (SPF, DKIM, DMARC)

---

## 📊 Debugging Steps

### Step 1: Enable Debug Mode

In `.env`:
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

### Step 2: Clear All Caches

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Step 3: Check Configuration

```bash
php artisan tinker
```

```php
config('mail.default')
// Should return: "mailgun"

config('services.mailgun.domain')
// Should return your domain

config('services.mailgun.secret')
// Should return your API key

config('mail.from.address')
// Should return your from address
```

### Step 4: Test Email Sending

```bash
php artisan test:email your-email@example.com
```

### Step 5: Check Logs

```bash
# Windows
type storage\logs\laravel.log | findstr "SendEmail"

# Or open in notepad
notepad storage\logs\laravel.log
```

---

## 🎯 Expected Workflow

### Successful Email Send:

```
1. User clicks 📧 button
   ↓
2. Browser sends POST request to /admin/leads/{id}/send-email
   ↓
3. Laravel processes request
   ↓
4. Email sent via Mailgun
   ↓
5. Lead status updated to "Email Sent"
   ↓
6. Activity logged in lead_logs
   ↓
7. Laravel redirects back (302)
   ↓
8. Success message displayed
   ↓
9. Lead status shows "Email Sent"
```

### Failed Email Send:

```
1. User clicks 📧 button
   ↓
2. Browser sends POST request
   ↓
3. Laravel processes request
   ↓
4. Error occurs (no template, no email, Mailgun error)
   ↓
5. Laravel catches exception
   ↓
6. Laravel redirects back (302)
   ↓
7. Error message displayed
   ↓
8. Lead status unchanged
```

---

## 🔍 Inspect Network Request

### Using Browser DevTools:

1. Open DevTools (F12)
2. Go to **Network** tab
3. Click 📧 button
4. Look for POST request to `/admin/leads/{id}/send-email`
5. Check response:
   - **Status: 302** = Normal redirect
   - **Location header** = Where it redirects to
6. Click on the request
7. Check **Response** tab for any error messages

---

## ✅ Verification Checklist

After clicking 📧 button, verify:

- [ ] Page reloads (302 redirect is normal)
- [ ] Success or error message appears at top
- [ ] Lead status changed to "Email Sent" (if successful)
- [ ] No error message displayed (if successful)
- [ ] Check Mailgun dashboard for sent email
- [ ] Check recipient inbox (and spam folder)

---

## 📝 Quick Test

1. **Create email template:**
   - Go to `/admin/email-templates`
   - Add template for activity type
   - Subject: `Test Email for {company_name}`
   - Body: `Hello {director}, this is a test.`

2. **Find lead with email:**
   - Go to `/admin/leads`
   - Find lead with email address
   - Note the activity type

3. **Send email:**
   - Click 📧 button
   - Wait for page reload
   - Check for success message

4. **Verify:**
   - Status changed to "Email Sent"?
   - Email received in inbox?
   - Check Mailgun logs

---

## 🚨 If Still Not Working

1. **Run test command:**
```bash
php artisan test:email your-email@example.com
```

2. **Check exact error:**
```bash
php artisan tail
```

3. **Verify Mailgun credentials:**
   - Login to https://app.mailgun.com/
   - Check domain is verified
   - Check API key is correct
   - Check sending limits

4. **Test with different email:**
   - Try sending to different email address
   - Check if it's email-specific issue

5. **Check Mailgun logs:**
   - Go to https://app.mailgun.com/app/logs
   - Look for your email
   - Check delivery status

---

## 📞 Support

If email still not working after all checks:

1. Share the output of:
```bash
php artisan test:email your-email@example.com
```

2. Share any error messages from:
```bash
type storage\logs\laravel.log | findstr "SendEmail"
```

3. Check Mailgun dashboard for:
   - Domain verification status
   - API key validity
   - Sending limits
   - Recent logs

---

**Remember:** 302 redirect is NORMAL. Check for success/error messages and lead status change!
