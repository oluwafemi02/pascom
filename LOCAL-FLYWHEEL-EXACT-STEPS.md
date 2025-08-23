# 🎯 Exact Steps for Local by Flywheel - frenchie-allergy-help

## Step 1: Open Your Site Folder in Local

1. Open the **Local** app
2. Find your site: **frenchie-allergy-help**
3. Click the **arrow** next to your site name to expand options
4. Click **"Go to site folder"** button

This will open a file explorer/finder window showing:
```
📁 frenchie-allergy-help/
  ├── 📁 app/
  │   ├── 📁 public/    ← This is your WordPress root
  │   └── 📁 sql/
  ├── 📁 conf/
  └── 📁 logs/
```

## Step 2: Navigate to wp-content

1. In the opened folder, go to: `app` → `public` → `wp-content`

Full path:
```
📁 frenchie-allergy-help/app/public/wp-content/
```

## Step 3: Copy the Automation Files

### From your cloned repository workspace:

1. Go to `/workspace/wordpress-upload-ready/`
2. Find these items:
   - 📁 `automation/` folder - copy just the `setup-automation.php` file from inside
   - 📁 `import-content/` folder - copy the entire folder

### To your Local site:

Copy them to: `frenchie-allergy-help/app/public/wp-content/`

After copying, your wp-content should look like:
```
📁 wp-content/
  ├── 📄 setup-automation.php     ← You just added this
  ├── 📁 import-content/          ← You just added this
  │   ├── 📁 site-content/
  │   └── 📁 legal-pages/
  ├── 📁 plugins/
  ├── 📁 themes/
  └── 📁 uploads/
```

## Step 4: Run the Automation Script

1. Go back to **Local** app
2. Make sure your site is **running** (green "STOP" button visible)
3. Click the **"VIEW SITE"** button to open your site
4. In your browser, change the URL to:
   ```
   http://frenchie-allergy-help.local/wp-content/setup-automation.php
   ```
5. Press Enter to run the script

## Step 5: Watch the Magic Happen! ✨

You should see:
```
🐾 Frenchie Allergy Help - Automated Setup
==========================================

Creating categories...
✅ Category 'Allergies' created
✅ Category 'Health' created
✅ Category 'Nutrition' created
✅ Category 'Grooming' created
✅ Category 'Lifestyle' created

Creating pages...
✅ Page 'Home' created
✅ Page 'About' created
✅ Page 'Start Here' created
✅ Page 'Contact' created
✅ Page 'Privacy Policy' created
✅ Page 'Affiliate Disclosure' created

Creating posts...
✅ Post 'Understanding French Bulldog Allergies' created
✅ Post 'Common Food Allergies in French Bulldogs' created

Setting up menus...
✅ Primary menu created
✅ Footer menu created

Configuring settings...
✅ Homepage set
✅ Permalinks configured

Setup completed successfully!
```

## Step 6: Clean Up (IMPORTANT!)

1. Go back to your file explorer
2. Navigate to: `frenchie-allergy-help/app/public/wp-content/`
3. **DELETE** the `setup-automation.php` file (keep import-content for now)

## Step 7: Verify Everything Worked

1. Go to your WordPress admin: `http://frenchie-allergy-help.local/wp-admin/`
2. Check:
   - **Pages** → All Pages (should see 6 pages)
   - **Posts** → All Posts (should see 2 posts)
   - **Posts** → Categories (should see 5 categories)
   - **Appearance** → Menus (should see 2 menus)

## Troubleshooting

### If the script won't run:
1. Make sure your site is running in Local
2. Check the URL is exactly: `http://frenchie-allergy-help.local/wp-content/setup-automation.php`
3. Make sure you copied both files to the right location

### If you see "File not found":
- The `import-content` folder must be in the same directory as `setup-automation.php`
- Both should be directly in `wp-content/`

### If nothing imports:
1. Check PHP error log in Local (click "Logs" → "PHP")
2. Try running the script again
3. Make sure the import-content folder has the HTML files

## Quick Terminal Alternative (if comfortable)

```bash
# From your workspace
cd /workspace

# Copy the files
cp wordpress-upload-ready/automation/setup-automation.php ~/Local\ Sites/frenchie-allergy-help/app/public/wp-content/
cp -r wordpress-upload-ready/import-content ~/Local\ Sites/frenchie-allergy-help/app/public/wp-content/

# Open in browser
open http://frenchie-allergy-help.local/wp-content/setup-automation.php
```

## Next Steps

After successful automation:
1. ✅ Theme and plugins should already be uploaded (from previous steps)
2. ✅ Activate your theme if not already active
3. ✅ Activate both plugins
4. ✅ Continue with "Essential Post-Setup" tasks from the checklist

You're almost there! 🎉