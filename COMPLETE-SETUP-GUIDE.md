# 🚀 Complete WordPress Setup Guide - Frenchie Allergy Help

## Overview

This guide provides both **automated** and **manual** methods to set up your Frenchie Allergy Help website on WordPress. Since you already have WordPress installed at http://frenchie-allergy-help.local/wp-admin/, we'll focus on deploying all the content and configurations.

## 📋 Pre-Setup Checklist

Before starting, ensure you have:
- ✅ WordPress installed and accessible
- ✅ Admin access to WordPress
- ✅ All files from this repository downloaded/cloned
- ✅ FTP/File access to your WordPress installation (for uploading themes/plugins)

## 🤖 Option 1: Automated Setup (Recommended)

### Method A: Using the PHP Automation Script

1. **Upload the automation script:**
   ```bash
   # Upload this file to your WordPress directory:
   wordpress-automation/setup-automation.php
   
   # Place it in one of these locations:
   - wp-content/setup-automation.php
   - Or in WordPress root directory
   ```

2. **Prepare content files:**
   ```bash
   # Upload the entire workspace to a temporary location on your server
   # The script needs access to:
   /site-content/
   /legal-pages/
   ```

3. **Run the script:**
   - **Via Browser:** Navigate to `http://frenchie-allergy-help.local/wp-content/setup-automation.php`
   - **Via WP-CLI:** `wp eval-file wp-content/setup-automation.php`

4. **What it does automatically:**
   - Creates all categories
   - Imports all pages from HTML files
   - Creates blog posts with proper categories
   - Sets up primary and footer menus
   - Configures permalink structure
   - Creates and sets homepage
   - Sets up blog page

### Method B: Using WP-CLI (Command Line)

1. **Install WP-CLI** (if not already installed):
   ```bash
   curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
   chmod +x wp-cli.phar
   sudo mv wp-cli.phar /usr/local/bin/wp
   ```

2. **Upload files:**
   ```bash
   # Upload these to your server:
   - wordpress-automation/wp-cli-setup.sh
   - /site-content/ directory
   - /legal-pages/ directory
   ```

3. **Run the script:**
   ```bash
   cd /path/to/wordpress
   chmod +x wp-cli-setup.sh
   ./wp-cli-setup.sh
   ```

## 📝 Option 2: Manual Setup

If automation doesn't work or you prefer manual control:

### Step 1: Upload Theme and Plugins

1. **Via FTP/File Manager:**
   ```
   Upload from: /frenchie-allergy-help/wp-content/themes/frenchie-care/
   Upload to: /wp-content/themes/frenchie-care/
   
   Upload from: /frenchie-allergy-help/wp-content/plugins/frenchie-email-automation/
   Upload to: /wp-content/plugins/frenchie-email-automation/
   
   Upload from: /frenchie-allergy-help/wp-content/plugins/frenchie-monetization/
   Upload to: /wp-content/plugins/frenchie-monetization/
   ```

2. **Activate in WordPress:**
   - Go to `Appearance → Themes` → Activate "Frenchie Care"
   - Go to `Plugins` → Activate both Frenchie plugins

### Step 2: Create Categories

Go to `Posts → Categories` and create:
- Food Allergies (slug: food-allergies)
- Environmental Allergies (slug: environmental-allergies)
- Skin Care (slug: skin-care)
- Product Reviews (slug: product-reviews)
- Treatment Guides (slug: treatment-guides)

### Step 3: Create Pages

Go to `Pages → Add New` for each:

1. **About Page**
   - Title: About
   - Copy content from: `/site-content/pages/about.html`
   - Slug: about

2. **Start Here**
   - Title: Start Here
   - Copy content from: `/site-content/pages/start-here.html`
   - Slug: start-here

3. **Privacy Policy**
   - Title: Privacy Policy
   - Copy content from: `/legal-pages/privacy-policy.html`
   - Slug: privacy-policy

4. **Affiliate Disclosure**
   - Title: Affiliate Disclosure
   - Copy content from: `/legal-pages/affiliate-disclosure.html`
   - Slug: affiliate-disclosure

5. **Medical Disclaimer**
   - Title: Medical Disclaimer
   - Copy content from: `/site-content/pages/medical-disclaimer.html`
   - Slug: medical-disclaimer

6. **Contact**
   - Title: Contact
   - Add Contact Form 7 shortcode or create form

### Step 4: Create Posts

Go to `Posts → Add New`:

1. **French Bulldog Food Allergies Guide**
   - Copy content from: `/site-content/articles/food-allergies-guide.html`
   - Categories: Food Allergies, Treatment Guides

2. **French Bulldog Seasonal Allergies Guide**
   - Copy content from: `/site-content/articles/seasonal-allergies-guide.html`
   - Categories: Environmental Allergies, Treatment Guides

### Step 5: Configure Settings

1. **Permalinks** (`Settings → Permalinks`):
   - Select "Post name"
   - Save changes

2. **Reading Settings** (`Settings → Reading`):
   - Your homepage displays: A static page
   - Homepage: Create new page with welcome content
   - Posts page: Create "Blog" page

3. **Discussion** (`Settings → Discussion`):
   - Uncheck "Allow people to submit comments on new posts"

### Step 6: Setup Menus

Go to `Appearance → Menus`:

1. **Create Primary Menu:**
   - Home (custom link to /)
   - Start Here
   - About
   - Contact

2. **Create Footer Menu:**
   - Privacy Policy
   - Affiliate Disclosure
   - Medical Disclaimer
   - Contact

## 🔌 Essential Plugins to Install

Go to `Plugins → Add New` and install:

1. **Yoast SEO** or **Rank Math** (SEO)
2. **Wordfence Security** (Security)
3. **UpdraftPlus** (Backups)
4. **WP Rocket** (Caching - Premium)
5. **Contact Form 7** (Forms)
6. **WP Mail SMTP** (Email delivery)
7. **Cookie Notice** (GDPR compliance)

## 📧 Email Configuration

1. Install WP Mail SMTP plugin
2. Configure with your preferred service:
   - SendGrid (recommended)
   - Mailgun
   - Amazon SES
   - SMTP server

3. Test email delivery in plugin settings

## 💰 Monetization Setup

### Affiliate Links
1. Go to `Frenchie Monetization → Affiliate Links`
2. Update each product with your real affiliate IDs:
   - Amazon Associates ID
   - Chewy affiliate ID
   - Other affiliate program IDs

### Ad Networks
1. **For beginners:** Start with Ezoic (no traffic minimum)
2. **At 50k sessions/month:** Apply for Mediavine
3. Install their plugin or add code to theme

## 🚀 Launch Checklist

### Technical
- [ ] Permalinks set to "Post name"
- [ ] SSL certificate installed
- [ ] Caching plugin configured
- [ ] Backup plugin scheduled
- [ ] Security plugin active
- [ ] XML sitemap generated

### Content
- [ ] All pages created and reviewed
- [ ] All posts published
- [ ] Menus configured
- [ ] Widgets set up
- [ ] Images optimized

### SEO
- [ ] Meta descriptions added
- [ ] Google Analytics connected
- [ ] Google Search Console verified
- [ ] Sitemap submitted

### Legal
- [ ] Privacy Policy updated with your details
- [ ] Cookie notice active
- [ ] Affiliate disclosure visible
- [ ] Medical disclaimer prominent

## 🆘 Troubleshooting

### Script Won't Run
- Check file permissions (755 for scripts)
- Ensure WordPress path is correct
- Check PHP memory limit
- Look for error messages

### Content Not Importing
- Verify file paths are correct
- Check file encoding (should be UTF-8)
- Ensure sufficient permissions

### Theme/Plugin Issues
- Check WordPress/PHP version compatibility
- Look for error messages in debug.log
- Deactivate other plugins to test conflicts

## 📊 Post-Launch Tasks

1. **Week 1:**
   - Monitor site performance
   - Fix any broken links
   - Start promoting on social media
   - Join French Bulldog Facebook groups

2. **Month 1:**
   - Publish 2-3 new articles weekly
   - Build email list
   - Apply for affiliate programs
   - Optimize based on analytics

3. **Month 3:**
   - Launch digital product
   - Consider YouTube channel
   - Scale content production
   - Apply for better ad networks

## 💡 Pro Tips

1. **Backup before making changes**
2. **Test email signup flow**
3. **Check mobile responsiveness**
4. **Monitor page load speed**
5. **Engage with your audience**

## 📞 Need Help?

- WordPress Codex: https://codex.wordpress.org
- Theme customization: Edit style.css
- Plugin documentation: Check each plugin's settings
- Community support: WordPress forums

---

Remember: The key to success is consistent, helpful content that solves real problems for French Bulldog owners. The technical setup is just the beginning - focus on building trust and providing value to your audience! 🐾