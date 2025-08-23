# 🚀 Quick Start Checklist - Frenchie Allergy Help

## Immediate Actions (15 minutes)

### 1️⃣ Prepare Files (2 minutes)
```bash
# Run this command in your workspace:
bash prepare-for-upload.sh
```
This creates `frenchie-wordpress-upload.zip` with everything organized.

### 2️⃣ Upload to WordPress (5 minutes)
1. **Upload Theme:**
   - Go to WordPress Admin → Appearance → Themes → Add New → Upload Theme
   - Upload: `themes/frenchie-care` folder
   - Activate the theme

2. **Upload Plugins:**
   - Go to Plugins → Add New → Upload Plugin
   - Upload both plugins from `plugins/` folder:
     - `frenchie-email-automation`
     - `frenchie-monetization`
   - Activate both plugins

### 3️⃣ Run Automation (8 minutes)

**Option A - Easiest (if you have file access):**
1. Upload `automation/setup-automation.php` to wp-content/
2. Upload `import-content/` folder to same location
3. Visit: `http://frenchie-allergy-help.local/wp-content/setup-automation.php`
4. Delete the setup file after completion

**Option B - REST API (run from anywhere):**
1. Edit `wordpress-automation/rest-api-setup.php`
2. Update username/password in config
3. Run: `php rest-api-setup.php`

## Essential Post-Setup (30 minutes)

### ✅ Technical Setup
- [ ] Set permalinks to "Post name" (Settings → Permalinks)
- [ ] Install Contact Form 7 plugin
- [ ] Install Yoast SEO or Rank Math
- [ ] Install WP Mail SMTP
- [ ] Configure email settings

### ✅ Content Check
- [ ] Review imported pages
- [ ] Check menus are displayed
- [ ] Test all links work
- [ ] Preview on mobile

### ✅ Monetization
- [ ] Add your Amazon affiliate ID
- [ ] Add other affiliate IDs
- [ ] Configure email automation
- [ ] Set up welcome email series

## First Day Tasks

### 🔒 Security & Performance
```
Plugins to install:
1. Wordfence Security
2. UpdraftPlus (backups)
3. WP Rocket or W3 Total Cache
4. Cookie Notice (GDPR)
```

### 📝 Legal Pages
- Update Privacy Policy with your details
- Ensure Affiliate Disclosure is visible
- Add Medical Disclaimer to footer

### 📊 Analytics
- Install Google Analytics
- Set up Google Search Console
- Submit XML sitemap

## Quick Troubleshooting

### ❓ Automation script not working?
- Check file permissions (755)
- Ensure PHP memory limit is sufficient
- Try the manual method instead

### ❓ Theme not showing correctly?
- Clear cache
- Check if plugins are activated
- Ensure WordPress is updated

### ❓ Content not importing?
- Verify file paths in scripts
- Check that HTML files exist
- Try importing one at a time

## Success Metrics

After setup, you should have:
- ✅ 6 pages created (About, Start Here, Contact, etc.)
- ✅ 2 blog posts published
- ✅ 5 categories created
- ✅ 2 menus configured
- ✅ Theme activated with plugins

## Next Steps

1. **Today:** Complete all items on this checklist
2. **This Week:** Add 5-10 more articles
3. **This Month:** Build email list to 100+ subscribers
4. **Month 3:** Launch premium content/toolkit

---

**Need detailed instructions?** See `COMPLETE-SETUP-GUIDE.md`
**Having issues?** Check troubleshooting in the complete guide

Remember: Focus on helping French Bulldog owners first, monetization follows naturally! 🐾