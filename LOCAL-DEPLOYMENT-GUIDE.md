# Frenchie Care Local Deployment Guide

This guide helps you automatically sync your GitHub repository with your Local by Flywheel WordPress installation.

## Prerequisites

1. **Local by Flywheel** installed and running
2. A site named `frenchie-allergy-help` created in Local
3. Site URL configured as `http://frenchie-allergy-help.local`
4. Git installed and configured
5. Access to the GitHub repository: https://github.com/oluwafemi02/pascom

## Initial Setup

### 1. Create Local Site

In Local by Flywheel:
- Click "Create a new site"
- Site name: `frenchie-allergy-help`
- Choose PHP 7.4+ and MySQL 5.7+
- WordPress username and password (remember these)
- Start the site

### 2. Clone Repository

```bash
git clone https://github.com/oluwafemi02/pascom.git
cd pascom
```

### 3. Enable Automation

Run this once to set up automatic deployment on git operations:

```bash
./cursor setup-hooks
```

## Usage

### Basic Commands

All commands are run through the `cursor` wrapper:

```bash
# Show available commands
./cursor help

# Deploy latest changes from GitHub to local
./cursor deploy-local

# Completely reset and reimport everything
./cursor reset-local

# Check deployment status
./cursor status

# Configure WordPress settings (after deployment)
./cursor configure
```

### Typical Workflow

1. **Make changes to your GitHub repository**
   - Edit themes, plugins, or content
   - Commit and push to `main` branch

2. **Update local site**
   ```bash
   git pull
   # If you enabled hooks, deployment runs automatically
   # Otherwise, run:
   ./cursor deploy-local
   ```

3. **Fresh install** (when needed)
   ```bash
   ./cursor reset-local
   ```

## What Gets Synced

### From Repository → Local WordPress

1. **Theme**: `wordpress-upload-ready/themes/frenchie-care/`
2. **Plugins**: 
   - `wordpress-upload-ready/plugins/frenchie-email-automation/`
   - `wordpress-upload-ready/plugins/frenchie-monetization/`
3. **Content**: Via `automation/setup-automation.php`
4. **Import Data**: `import-content/` directory

### Automatic Configuration

The deployment scripts automatically:
- Set permalinks to "Post name" structure
- Copy all theme and plugin files
- Run the automation script to import content
- Clean up temporary files

## Required WordPress Plugins

Make sure these are installed (not included in repo):
- Contact Form 7
- Yoast SEO (or Rank Math)
- WP Mail SMTP

After deployment, activate them:
```bash
./cursor configure
```

## Troubleshooting

### Local Site Not Found
```
Error: Local by Flywheel site not found
```
**Solution**: Create a site named `frenchie-allergy-help` in Local

### Site Not Accessible
```
Error: Could not connect to local site
```
**Solution**: Start the site in Local by Flywheel

### Automation Script Failed
```
Warning: Automation script returned HTTP XXX
```
**Solution**: 
1. Visit `http://frenchie-allergy-help.local/wp-content/setup-automation.php` manually
2. Check WordPress error logs
3. Ensure all import files exist

### Permission Errors
```
Permission denied
```
**Solution**: Make scripts executable:
```bash
chmod +x cursor scripts/*.sh
```

## File Structure

```
pascom/
├── cursor                          # Main command wrapper
├── scripts/
│   ├── deploy-local.sh            # Deployment script
│   ├── reset-local.sh             # Reset and fresh install
│   ├── configure-wordpress.sh     # WP configuration
│   └── setup-git-hooks.sh         # Git automation
├── wordpress-upload-ready/
│   ├── themes/
│   │   └── frenchie-care/         # WordPress theme
│   ├── plugins/
│   │   ├── frenchie-email-automation/
│   │   └── frenchie-monetization/
│   ├── automation/
│   │   └── setup-automation.php   # Content import script
│   └── import-content/            # Import data files
```

## Success Criteria

After successful deployment:
- ✓ Theme "Frenchie Care" is active
- ✓ Custom plugins are installed and active
- ✓ Permalinks are set to "Post name"
- ✓ Demo content is imported
- ✓ Menus and categories exist
- ✓ Site looks identical to production

## Advanced Usage

### Custom Local Site Path

If your Local site is in a different location, edit the path in scripts:
```bash
LOCAL_SITE_PATH="$HOME/Local Sites/your-site-name/app/public"
```

### Disable Auto-deployment

Remove git hooks:
```bash
rm .git/hooks/post-merge .git/hooks/post-checkout
```

### Manual Database Operations

Access WordPress database via Local:
1. Click on the site in Local
2. Go to "Database" tab
3. Click "Open Adminer"

## Need Help?

1. Check deployment status: `./cursor status`
2. Read error messages carefully
3. Ensure Local by Flywheel is running
4. Check file permissions
5. Verify git repository is up to date