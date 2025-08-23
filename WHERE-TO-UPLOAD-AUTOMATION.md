# 📍 Where to Upload setup-automation.php in WordPress

## Important: You DON'T upload through WordPress Admin!

The `setup-automation.php` file needs to be uploaded directly to your WordPress server files, NOT through the WordPress admin dashboard. Here's how:

## Method 1: Using Web Hosting Control Panel (cPanel/Plesk)

### Step 1: Log into your hosting control panel
- Go to your hosting provider's website
- Log into your account
- Look for "cPanel", "Control Panel", or "File Manager"

### Step 2: Open File Manager
- Click on "File Manager" icon
- Navigate to your WordPress installation folder
  - Usually: `public_html/` or `www/` or `htdocs/`
  - If WordPress is in a subdirectory: `public_html/your-site/`

### Step 3: Find wp-content folder
```
Your WordPress files structure:
📁 public_html/ (or www/ or htdocs/)
  ├── 📁 wp-admin/
  ├── 📁 wp-content/      ← UPLOAD HERE!
  │   ├── 📁 plugins/
  │   ├── 📁 themes/
  │   └── 📁 uploads/
  ├── 📁 wp-includes/
  ├── 📄 wp-config.php
  └── 📄 index.php
```

### Step 4: Upload the files
1. Click on `wp-content` folder to enter it
2. Click "Upload" button in file manager
3. Upload these from your local `wordpress-upload-ready/` folder:
   - `automation/setup-automation.php` (just this file)
   - `import-content/` (entire folder)

After upload, your wp-content should look like:
```
📁 wp-content/
  ├── 📄 setup-automation.php     ← Your uploaded file
  ├── 📁 import-content/          ← Your uploaded folder
  │   ├── 📁 site-content/
  │   └── 📁 legal-pages/
  ├── 📁 plugins/
  ├── 📁 themes/
  └── 📁 uploads/
```

## Method 2: Using FTP/SFTP Client (FileZilla)

### Step 1: Install FTP client
- Download FileZilla from https://filezilla-project.org/
- Install and open it

### Step 2: Connect to your server
You need these details from your hosting provider:
- Host: ftp.your-domain.com (or IP address)
- Username: your-ftp-username
- Password: your-ftp-password
- Port: 21 (or 22 for SFTP)

### Step 3: Navigate to wp-content
1. In the "Remote site" panel (right side), navigate to:
   - `/public_html/wp-content/` or
   - `/www/wp-content/` or
   - `/htdocs/wp-content/`

### Step 4: Upload files
1. In the "Local site" panel (left side), navigate to your cloned repo
2. Find `wordpress-upload-ready/automation/setup-automation.php`
3. Right-click and select "Upload"
4. Also upload the `import-content/` folder

## Method 3: For Local Development (XAMPP/MAMP/Local)

### If using XAMPP:
```
C:\xampp\htdocs\your-wordpress-site\wp-content\
```

### If using MAMP:
```
/Applications/MAMP/htdocs/your-wordpress-site/wp-content/
```

### If using Local by Flywheel:
1. Open Local app
2. Click on your site
3. Click "Go to site folder"
4. Navigate to `app/public/wp-content/`

## Method 4: Using WordPress Hosting Tools

### WP Engine:
1. Log into WP Engine dashboard
2. Go to your site → SFTP users
3. Use their provided SFTP details with FileZilla

### SiteGround:
1. Log into SiteGround
2. Go to Site Tools → File Manager
3. Navigate to `public_html/wp-content/`

### Bluehost:
1. Log into Bluehost
2. Go to Advanced → File Manager
3. Navigate to `public_html/wp-content/`

## ⚠️ Common Mistakes to Avoid

### ❌ DON'T upload through:
- WordPress Media Library
- WordPress Plugin uploader
- WordPress Theme uploader

### ✅ DO upload directly to:
- Server file system via FTP/File Manager
- The `wp-content/` directory specifically

## After Uploading

1. Open your browser
2. Go to: `http://your-site.com/wp-content/setup-automation.php`
3. You should see the automation script running
4. **DELETE the setup-automation.php file after it completes!**

## Quick Checklist

- [ ] Located your WordPress installation folder
- [ ] Found the `wp-content/` directory
- [ ] Uploaded `setup-automation.php` to `wp-content/`
- [ ] Uploaded `import-content/` folder to `wp-content/`
- [ ] Ran the script via browser
- [ ] Deleted `setup-automation.php` after completion

## Still Confused?

If you're unsure about file access:
1. Contact your hosting provider's support
2. Ask them: "How do I upload files to my wp-content directory?"
3. They can provide specific instructions for their platform

Or use Option B (REST API method) instead, which doesn't require file uploads to the server!