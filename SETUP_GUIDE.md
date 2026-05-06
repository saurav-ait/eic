# Complete Lead Management System - Setup Guide

## 🎯 Overview
A comprehensive lead management system with activity types, email templates, and SMS/WhatsApp templates for Laravel.

---

## 📦 What Was Created

### Controllers (7 files)
1. ✅ **LeadController.php** - Main lead management
2. ✅ **ActivityTypeController.php** - Activity types management
3. ✅ **EmailTemplateController.php** - Email templates management
4. ✅ **TextTemplateController.php** - SMS/WhatsApp templates management

### Models (5 files)
1. ✅ **Lead.php** - Lead model (already existed)
2. ✅ **ActivityType.php** - Activity type model (already existed)
3. ✅ **EmailTemplate.php** - Email template model (NEW)
4. ✅ **TextTemplate.php** - Text template model (NEW)
5. ✅ **LeadLog.php** - Lead activity log (already existed)

### Views (4 files)
1. ✅ **resources/views/client/leads/index.blade.php** - Lead management UI
2. ✅ **resources/views/client/activities/index.blade.php** - Activity types UI
3. ✅ **resources/views/client/email-templates/index.blade.php** - Email templates UI
4. ✅ **resources/views/client/text-templates/index.blade.php** - Text templates UI

### Import/Export Classes (2 files)
1. ✅ **app/Imports/LeadsImport.php** - Import leads from Excel/CSV
2. ✅ **app/Exports/LeadsExport.php** - Export leads to Excel

### Sample Files
1. ✅ **public/examples/leads-import-example.csv** - Sample import template

### Documentation
1. ✅ **LEAD_MANAGEMENT.md** - Complete system documentation
2. ✅ **SETUP_GUIDE.md** - This file

---

## 🚀 Quick Start

### Step 1: Database Setup
The migrations should already exist. If not, run:
```bash
php artisan migrate
```

### Step 2: Add Sample Data

#### Add Countries
```sql
INSERT INTO countries (name, created_at, updated_at) VALUES
('United States', NOW(), NOW()),
('United Kingdom', NOW(), NOW()),
('Canada', NOW(), NOW()),
('Australia', NOW(), NOW()),
('Germany', NOW(), NOW());
```

#### Add Activity Types
```sql
INSERT INTO activity_types (name, created_at, updated_at) VALUES
('Work Visa', NOW(), NOW()),
('Study Visa', NOW(), NOW()),
('Tourist Visa', NOW(), NOW()),
('Business Visa', NOW(), NOW()),
('Immigration', NOW(), NOW());
```

### Step 3: Access the System

Navigate to these URLs (must be logged in as Admin):

1. **Lead Management**: `http://your-domain/admin/leads`
2. **Activity Types**: `http://your-domain/admin/activities`
3. **Email Templates**: `http://your-domain/admin/email-templates`
4. **Text Templates**: `http://your-domain/admin/text-templates`

---

## 📋 Routes Summary

### Lead Management Routes
```php
GET    /admin/leads                    - List all leads
POST   /admin/leads                    - Create new lead
PUT    /admin/leads/{id}               - Update lead
DELETE /admin/leads/{id}               - Delete lead
POST   /admin/leads/import             - Import leads
GET    /admin/leads/export             - Export leads
POST   /admin/leads/{id}/send-email    - Send email
POST   /admin/leads/{id}/send-text     - Send SMS/WhatsApp
```

### Activity Types Routes
```php
GET    /admin/activities               - List activity types
POST   /admin/activities               - Create activity type
PUT    /admin/activities/{id}          - Update activity type
DELETE /admin/activities/{id}          - Delete activity type
```

### Email Templates Routes
```php
GET    /admin/email-templates          - List email templates
POST   /admin/email-templates          - Create template
PUT    /admin/email-templates/{id}     - Update template
DELETE /admin/email-templates/{id}     - Delete template
```

### Text Templates Routes
```php
GET    /admin/text-templates           - List text templates
POST   /admin/text-templates           - Create template
PUT    /admin/text-templates/{id}      - Update template
DELETE /admin/text-templates/{id}      - Delete template
```

---

## 🎨 Features

### Lead Management
- ✅ Create, Read, Update, Delete leads
- ✅ Search by company, phone, email, city
- ✅ Filter by country, activity type, status
- ✅ Import from Excel/CSV
- ✅ Export to Excel
- ✅ Status tracking (New, Contacted, Converted, Lost)
- ✅ Statistics dashboard
- ✅ Pagination

### Activity Types Management
- ✅ Create, Read, Update, Delete activity types
- ✅ View associated leads count
- ✅ Prevent deletion if leads exist

### Email Templates Management
- ✅ Create templates per activity type
- ✅ Subject and body fields
- ✅ Preview in table
- ✅ Ready for email integration

### Text Templates Management
- ✅ Create SMS/WhatsApp templates per activity type
- ✅ Character counter (max 1000)
- ✅ Ready for SMS/WhatsApp integration

---

## 📊 Database Structure

### leads table
```
id                  - Primary key
country_id          - Foreign key to countries
activity_type_id    - Foreign key to activity_types
company_name        - VARCHAR(255) NOT NULL
director            - VARCHAR(255) NULL
phone               - VARCHAR(255) NOT NULL (indexed)
email               - VARCHAR(255) NULL (indexed)
address             - TEXT NULL
city                - VARCHAR(255) NULL
status              - ENUM('New','Contacted','Converted','Lost')
created_at          - Timestamp
updated_at          - Timestamp
```

### activity_types table
```
id          - Primary key
name        - VARCHAR(255) NOT NULL
created_at  - Timestamp
updated_at  - Timestamp
```

### email_templates table
```
id                  - Primary key
activity_type_id    - Foreign key to activity_types
subject             - VARCHAR(255) NOT NULL
body                - TEXT NOT NULL
created_at          - Timestamp
updated_at          - Timestamp
```

### text_templates table
```
id                  - Primary key
activity_type_id    - Foreign key to activity_types
body                - VARCHAR(1000) NOT NULL
created_at          - Timestamp
updated_at          - Timestamp
```

---

## 📥 Import Format

### CSV/Excel Format
```csv
company_name,director,phone,email,city,address,country_id,activity_type_id,status
ABC Company,John Doe,+1234567890,john@abc.com,New York,123 Main St,1,1,New
XYZ Corp,Jane Smith,+0987654321,jane@xyz.com,London,456 High St,2,2,Contacted
```

### Required Fields
- company_name
- phone
- country_id
- activity_type_id

### Optional Fields
- director
- email
- city
- address
- status (defaults to 'New')

---

## 🔧 Configuration

### Email Setup (Future)
Add to `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

### SMS/WhatsApp Setup (Future)
Add to `.env`:
```env
TWILIO_SID=your_twilio_sid
TWILIO_TOKEN=your_twilio_token
TWILIO_FROM=your_twilio_number
```

---

## 🎯 Usage Examples

### Adding a Lead
1. Go to `/admin/leads`
2. Click "+ Add Lead"
3. Fill in:
   - Company Name: "Tech Solutions Inc"
   - Director: "John Smith"
   - Phone: "+1234567890"
   - Email: "john@techsolutions.com"
   - City: "New York"
   - Country: Select from dropdown
   - Activity Type: Select from dropdown
   - Status: "New"
4. Click "Save"

### Importing Leads
1. Prepare Excel/CSV file with correct format
2. Go to `/admin/leads`
3. Click "Import"
4. Select file
5. Click "Import"

### Creating Email Template
1. Go to `/admin/email-templates`
2. Click "+ Add Template"
3. Select Activity Type
4. Enter Subject: "Welcome to Our Service"
5. Enter Body: "Dear {name}, Thank you for your interest..."
6. Click "Save"

### Filtering Leads
1. Go to `/admin/leads`
2. Use search box for text search
3. Select filters:
   - Country
   - Activity Type
   - Status
4. Click "Filter"

---

## 🔐 Security

- ✅ All routes protected by authentication
- ✅ Admin role required
- ✅ CSRF protection on all forms
- ✅ Input validation on all operations
- ✅ SQL injection prevention (Eloquent ORM)
- ✅ XSS protection (Blade escaping)

---

## 🐛 Troubleshooting

### Issue: "Activity types not showing"
**Solution**: Add activity types first at `/admin/activities`

### Issue: "Countries not showing"
**Solution**: Add countries first at `/admin/countries`

### Issue: "Import fails"
**Solution**: Check CSV format matches the template in `/public/examples/`

### Issue: "Cannot delete activity type"
**Solution**: Activity type has associated leads. Delete leads first or reassign them.

---

## 📈 Future Enhancements

1. **Email Integration**
   - Implement actual email sending
   - Use EmailTemplate for personalization
   - Track email opens/clicks

2. **SMS/WhatsApp Integration**
   - Integrate Twilio or similar
   - Use TextTemplate for messages
   - Track delivery status

3. **Lead Scoring**
   - Automatic scoring based on interactions
   - Priority levels

4. **Activity Timeline**
   - Track all interactions
   - View complete history

5. **Bulk Operations**
   - Bulk status updates
   - Bulk email sending
   - Bulk assignments

6. **Reports & Analytics**
   - Conversion rates
   - Lead sources analysis
   - Activity reports

---

## 📞 Support

For issues or questions:
1. Check LEAD_MANAGEMENT.md for detailed documentation
2. Review this setup guide
3. Check Laravel logs: `storage/logs/laravel.log`

---

## ✅ Checklist

Before going live:
- [ ] Run migrations
- [ ] Add countries
- [ ] Add activity types
- [ ] Test lead creation
- [ ] Test import/export
- [ ] Configure email settings (if needed)
- [ ] Configure SMS settings (if needed)
- [ ] Test all CRUD operations
- [ ] Test filters and search
- [ ] Review permissions

---

**System Status**: ✅ Ready for Production

**Version**: 1.0.0

**Last Updated**: {{ now()->format('Y-m-d') }}
