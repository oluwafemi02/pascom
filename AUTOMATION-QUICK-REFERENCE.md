# 🚀 Automation Quick Reference

## Option A: Direct Upload (Recommended for Most Users)

### What You Need:
- Access to WordPress files (FTP/cPanel/Local)
- The `frenchie-wordpress-upload.zip` file

### Quick Steps:
1. Extract `automation/setup-automation.php` from the zip
2. Extract `import-content/` folder from the zip
3. Upload both to your `wp-content/` directory
4. Visit: `http://your-site.com/wp-content/setup-automation.php`
5. **Delete setup-automation.php after completion!**

### File Structure:
```
wp-content/
├── setup-automation.php    ← Upload this file
├── import-content/         ← Upload this folder
│   ├── site-content/
│   └── legal-pages/
```

## Option B: REST API (For Remote Setup)

### What You Need:
- PHP installed on your computer
- WordPress admin credentials
- The automation files from this repository

### Quick Steps:
1. Edit `wordpress-automation/rest-api-setup.php`
2. Update these lines:
   ```php
   'site_url' => 'http://your-site.com',
   'username' => 'your_username',
   'password' => 'your_password',
   ```
3. Run: `php rest-api-setup.php`

### Troubleshooting REST API:
- Enable Application Passwords in WordPress
- Check if host allows REST API
- Use application password instead of regular password

## What Gets Created:

✅ **6 Pages:** Home, About, Start Here, Contact, Privacy, Disclosure
✅ **2 Blog Posts:** Allergy guides for French Bulldogs
✅ **5 Categories:** Allergies, Health, Nutrition, Grooming, Lifestyle
✅ **2 Menus:** Primary (header) and Footer menus

## Common Issues & Fixes:

| Issue | Fix |
|-------|-----|
| Script won't load | Check file paths and permissions (755) |
| Authentication fails | Use application password, check credentials |
| Content missing | Verify import-content folder is uploaded |
| Menu not showing | Check theme supports menus |

## Success Indicators:
- ✅ Script shows "Setup completed successfully!"
- ✅ All pages visible in WordPress admin
- ✅ Menus appear in Appearance → Menus
- ✅ Homepage shows as static page

## After Automation:
1. Delete setup files (security!)
2. Install essential plugins
3. Configure email settings
4. Add affiliate IDs
5. Start creating more content!

---
**Full Guide:** See `RUN-AUTOMATION-GUIDE.md` for detailed instructions