# 🚀 Run Automation - Detailed Guide

## Overview
This guide provides comprehensive instructions for running the automation scripts to set up your Frenchie Allergy Help website content.

## 📁 Pre-Setup Verification

Before running automation, verify your file structure:

```
wordpress-upload-ready/
├── automation/
│   └── setup-automation.php
├── import-content/
│   ├── site-content/
│   │   ├── pages/
│   │   └── articles/
│   └── legal-pages/
├── themes/
└── plugins/
```

## 🔧 Option A: Direct File Access Method

### Step 1: Upload Files
1. Connect to your WordPress site via FTP/cPanel/File Manager
2. Navigate to `wp-content/` directory
3. Upload these folders:
   - `automation/` folder (contains setup-automation.php)
   - `import-content/` folder (contains all content)

### Step 2: Verify Upload
Your structure should look like:
```
wp-content/
├── automation/
│   └── setup-automation.php
├── import-content/
│   ├── site-content/
│   └── legal-pages/
├── themes/
├── plugins/
└── uploads/
```

### Step 3: Run the Script
1. Open your browser
2. Navigate to: `http://your-site.com/wp-content/automation/setup-automation.php`
3. You should see a progress report as content is imported

### Step 4: Clean Up
**Important**: Delete the automation folder after successful completion:
```bash
rm -rf wp-content/automation/
```

## 🌐 Option B: REST API Method

### Prerequisites
- PHP installed on your local machine or server
- WordPress REST API enabled (default in modern WordPress)
- Admin credentials or application password

### Step 1: Configure the Script
Edit `wordpress-automation/rest-api-setup.php`:

```php
$config = [
    'site_url' => 'https://your-actual-site.com', // Replace with your URL
    'username' => 'your_admin_username',          // Your WordPress admin
    'password' => 'your_app_password',           // Application password recommended
    'content_path' => __DIR__ . '/../site-content/',
    'legal_path' => __DIR__ . '/../legal-pages/'
];
```

### Step 2: Create Application Password
1. Go to WordPress Admin → Users → Your Profile
2. Scroll to "Application Passwords"
3. Enter name: "Automation Script"
4. Click "Add New Application Password"
5. Copy the generated password (spaces included)

### Step 3: Run the Script
```bash
cd wordpress-automation
php rest-api-setup.php
```

## 🛠️ Option C: WP-CLI Method (Advanced)

If you have SSH access and WP-CLI installed:

```bash
# Upload the script to your WordPress root
scp setup-automation.php user@server:/path/to/wordpress/

# SSH into your server
ssh user@server

# Navigate to WordPress root
cd /path/to/wordpress/

# Run via WP-CLI
wp eval-file setup-automation.php
```

## ⚠️ Common Issues & Solutions

### Issue: "Content directory not found"
**Solution**: Ensure `import-content` folder is uploaded alongside `automation` folder

### Issue: "wp-load.php not found"
**Solution**: 
- Verify script is in `wp-content/` directory
- Try accessing from `wp-content/mu-plugins/` instead

### Issue: "Authentication failed" (REST API)
**Solution**:
- Use application password, not regular password
- Ensure REST API is not blocked by security plugins
- Check if site uses HTTP Basic Auth

### Issue: "Memory exhausted"
**Solution**: Add to wp-config.php:
```php
define('WP_MEMORY_LIMIT', '256M');
```

### Issue: "Maximum execution time exceeded"
**Solution**: Add to script beginning:
```php
set_time_limit(300); // 5 minutes
```

## 📊 Expected Results

After successful automation:

✅ **Pages Created** (6 total):
- About French Bulldog Allergies
- Start Here - New Owner's Guide
- Resources & Tools
- Medical Disclaimer
- Privacy Policy
- Affiliate Disclosure
- Contact

✅ **Blog Posts Created** (2 total):
- Understanding French Bulldog Allergies: A Complete Guide
- The Frenchie's Health Journey

✅ **Categories Created** (5 total):
- Allergy Types
- Symptoms & Diagnosis
- Treatment & Management
- Diet & Nutrition
- Product Reviews

✅ **Menus Configured** (2 total):
- Primary Menu
- Footer Menu

## 🔐 Security Best Practices

1. **Always delete automation scripts after use**
2. **Use application passwords instead of main password**
3. **Run automation over HTTPS if possible**
4. **Backup your site before running automation**

## 🚦 Quick Checklist

Before running automation:
- [ ] WordPress installed and accessible
- [ ] Admin credentials ready
- [ ] Files uploaded correctly
- [ ] Backup created

During automation:
- [ ] Monitor progress output
- [ ] Check for error messages
- [ ] Verify content is importing

After automation:
- [ ] Delete automation scripts
- [ ] Verify all content imported
- [ ] Check menus are displaying
- [ ] Test page links

## 💡 Pro Tips

1. **Test on staging first**: If possible, run on a staging site before production
2. **Use browser console**: Check for JavaScript errors if script seems stuck
3. **Check PHP error log**: Look for detailed error messages
4. **Incremental import**: If failing, try importing one content type at a time

## 🆘 Need More Help?

If automation fails:
1. Check PHP error logs
2. Verify file permissions (755 for directories, 644 for files)
3. Try manual content import as fallback
4. Contact your hosting provider about script execution limits

Remember: The automation script is a convenience tool. All content can also be imported manually through the WordPress admin if needed.