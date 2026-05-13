# Lead Management System - Quick Reference

## 🔗 URLs (Admin Access Required)

| Module | URL | Description |
|--------|-----|-------------|
| **Leads** | `/admin/leads` | Manage all leads |
| **Activity Types** | `/admin/activities` | Manage activity types |
| **Email Templates** | `/admin/email-templates` | Manage email templates |
| **Text Templates** | `/admin/text-templates` | Manage SMS/WhatsApp templates |

---

## 📁 File Locations

### Controllers
```
app/Http/Controllers/LeadController.php
app/Http/Controllers/ActivityTypeController.php
app/Http/Controllers/EmailTemplateController.php
app/Http/Controllers/TextTemplateController.php
```

### Models
```
app/Models/Lead.php
app/Models/ActivityType.php
app/Models/EmailTemplate.php
app/Models/TextTemplate.php
app/Models/LeadLog.php
```

### Views
```
resources/views/client/leads/index.blade.php
resources/views/client/activities/index.blade.php
resources/views/client/email-templates/index.blade.php
resources/views/client/text-templates/index.blade.php
```

### Import/Export
```
app/Imports/LeadsImport.php
app/Exports/LeadsExport.php
public/examples/leads-import-example.csv
```

---

## 🎯 Quick Actions

### Add Sample Countries (SQL)
```sql
INSERT INTO countries (name, created_at, updated_at) VALUES
('United States', NOW(), NOW()),
('United Kingdom', NOW(), NOW()),
('Canada', NOW(), NOW());
```

### Add Sample Activity Types (SQL)
```sql
INSERT INTO activity_types (name, created_at, updated_at) VALUES
('Work Visa', NOW(), NOW()),
('Study Visa', NOW(), NOW()),
('Tourist Visa', NOW(), NOW());
```

### Add Sample Lead (SQL)
```sql
INSERT INTO leads (country_id, activity_type_id, company_name, phone, status, created_at, updated_at) 
VALUES (1, 1, 'Test Company', '+1234567890', 'New', NOW(), NOW());
```

---

## 🔑 Key Features

### Leads Module
- ✅ CRUD operations
- ✅ Search & filter
- ✅ Import/Export Excel
- ✅ Status tracking
- ✅ Statistics dashboard

### Activity Types Module
- ✅ CRUD operations
- ✅ Lead count tracking
- ✅ Deletion protection

### Email Templates Module
- ✅ CRUD operations
- ✅ Per activity type
- ✅ Subject & body

### Text Templates Module
- ✅ CRUD operations
- ✅ Per activity type
- ✅ Character counter

---

## 📊 Lead Status Values
- `New` - Newly added lead
- `Contacted` - Lead has been contacted
- `Converted` - Lead converted to customer
- `Lost` - Lead lost/not interested

---

## 🔍 Search & Filter

### Search Fields
- Company name
- Phone
- Email
- City

### Filter Options
- Country
- Activity Type
- Status

---

## 📥 Import CSV Format
```csv
company_name,director,phone,email,city,address,country_id,activity_type_id,status
ABC Corp,John Doe,+1234567890,john@abc.com,NYC,123 St,1,1,New
```

**Required**: company_name, phone, country_id, activity_type_id

---

## 🛠️ Common Commands

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Run Migrations
```bash
php artisan migrate
```

### Check Routes
```bash
php artisan route:list | grep leads
php artisan route:list | grep activities
```

---

## 🐛 Quick Fixes

### "Activity types not showing"
→ Add activity types at `/admin/activities`

### "Countries not showing"
→ Add countries at `/admin/countries`

### "Import fails"
→ Check CSV format matches template

### "Cannot delete activity type"
→ Has associated leads, delete/reassign first

---

## 📞 Route Names

### Leads
```
leads.index
leads.store
leads.update
leads.destroy
leads.import
leads.export
leads.send-email
leads.send-text
```

### Activity Types
```
activities.index
activities.store
activities.update
activities.destroy
```

### Email Templates
```
email-templates.index
email-templates.store
email-templates.update
email-templates.destroy
```

### Text Templates
```
text-templates.index
text-templates.store
text-templates.update
text-templates.destroy
```

---

## ✅ Pre-Launch Checklist

- [ ] Migrations run
- [ ] Countries added
- [ ] Activity types added
- [ ] Test lead creation
- [ ] Test import/export
- [ ] Test all filters
- [ ] Test templates
- [ ] Review permissions

---

**Quick Start**: Add countries → Add activity types → Add leads → Create templates

**Documentation**: See `LEAD_MANAGEMENT.md` and `SETUP_GUIDE.md`
