# 📋 Running the WordPress Automation - Detailed Guide

This guide explains both automation options from the "3️⃣ Run Automation" section in detail.

## Overview

The automation script will automatically:
- Create 6 pages (About, Start Here, Contact, Privacy Policy, Affiliate Disclosure, Medical Disclaimer)
- Create 2 blog posts with French Bulldog allergy content
- Set up 5 categories
- Configure 2 menus (Primary and Footer)
- Set your homepage and blog page
- Configure basic WordPress settings

## Option A: Direct File Upload (Easiest Method)

### Step 1: Prepare the Files
You should already have `frenchie-wordpress-upload.zip` from running `prepare-for-upload.sh`.

### Step 2: Access Your WordPress Files
You'll need access to your WordPress files via:
- FTP/SFTP client (like FileZilla)
- Web hosting control panel file manager
- Local development environment file system

### Step 3: Upload the Automation Files
1. Navigate to your WordPress `wp-content/` directory
2. Upload these from the `wordpress-upload-ready` folder:
   - `automation/setup-automation.php` file
   - `import-content/` folder (entire folder with its contents)

Your structure should look like:
```
wp-content/
├── setup-automation.php
├── import-content/
│   ├── site-content/
│   └── legal-pages/
├── plugins/
├── themes/
└── ...
```

### Step 4: Run the Script
1. Open your web browser
2. Navigate to: `http://your-domain.com/wp-content/setup-automation.php`
   - Replace `your-domain.com` with your actual domain
   - For local development: `http://frenchie-allergy-help.local/wp-content/setup-automation.php`

### Step 5: Watch the Output
The script will display:
```
🐾 Frenchie Allergy Help - Automated Setup
==========================================

Creating categories...
✅ Category 'Allergies' created
✅ Category 'Health' created
...

Creating pages...
✅ Page 'About' created
✅ Page 'Start Here' created
...

Setup completed successfully!
```

### Step 6: Delete the Setup File
**IMPORTANT:** After successful completion, delete `setup-automation.php` from wp-content/ for security.

## Option B: REST API Method (Remote Setup)

### Step 1: Prepare the Script
1. Open `/workspace/wordpress-automation/rest-api-setup.php`
2. Update the configuration section (lines 14-20):

```php
$config = [
    'site_url' => 'http://your-wordpress-site.com', // Your actual WordPress URL
    'username' => 'your_admin_username', // Your WordPress admin username
    'password' => 'your_admin_password', // Your WordPress admin password
    'content_path' => __DIR__ . '/../site-content/',
    'legal_path' => __DIR__ . '/../legal-pages/'
];
```

### Step 2: Enable Application Passwords (if needed)
1. Log into WordPress admin
2. Go to Users → Your Profile
3. Scroll to "Application Passwords"
4. Create a new application password
5. Use this password in the config instead of your regular password

### Step 3: Run the Script
From your terminal:
```bash
cd /workspace/wordpress-automation
php rest-api-setup.php
```

### Step 4: Monitor Progress
You'll see output like:
```
🐾 WordPress REST API Setup
==========================

✅ Authentication successful

Creating categories...
✅ Created category: Allergies
✅ Created category: Health
...
```

## Troubleshooting Common Issues

### "File not found" errors
- Ensure the `import-content` folder is in the same directory as `setup-automation.php`
- Check file permissions (755 for directories, 644 for files)

### "Cannot connect to database" errors
- The script needs to load WordPress - ensure wp-load.php path is correct
- Try moving the script to different locations if needed

### REST API authentication fails
- Enable application passwords in WordPress
- Check if REST API is enabled on your host
- Verify username and password are correct

### Content not importing
- Check PHP memory limit (increase if needed)
- Try importing one section at a time
- Verify HTML files exist in import-content folders

## What Happens During Automation

1. **Categories Created:**
   - Allergies
   - Health
   - Nutrition
   - Grooming
   - Lifestyle

2. **Pages Created:**
   - Home (set as front page)
   - About
   - Start Here
   - Contact
   - Privacy Policy
   - Affiliate Disclosure

3. **Posts Created:**
   - "Understanding French Bulldog Allergies: A Complete Guide"
   - "Common Food Allergies in French Bulldogs"

4. **Menus Configured:**
   - Primary Menu: Home, About, Start Here, Contact
   - Footer Menu: Privacy Policy, Affiliate Disclosure, Contact

5. **Settings Updated:**
   - Permalinks set to "Post name"
   - Homepage set to static page
   - Blog page configured

## Post-Automation Checklist

After the automation completes successfully:

- [ ] Verify all pages were created (check Pages → All Pages)
- [ ] Check that menus appear correctly
- [ ] Test all internal links
- [ ] Review imported content for formatting
- [ ] Activate your theme if not already active
- [ ] Activate both plugins (email automation & monetization)

## Next Steps

Once automation is complete, continue with the "Essential Post-Setup" tasks:
1. Install required plugins (Contact Form 7, SEO plugin, etc.)
2. Configure email settings
3. Add your affiliate IDs
4. Set up analytics

## Need Help?

If you encounter issues:
1. Check the browser console for JavaScript errors
2. Enable WordPress debug mode for detailed errors
3. Try the alternative setup method
4. Review server error logs

Remember: The automation script is just the beginning. Focus on creating valuable content for French Bulldog owners! 🐾