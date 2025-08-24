#!/bin/bash

# deploy-local.sh - Sync GitHub repo to Local by Flywheel WordPress installation
# Usage: ./scripts/deploy-local.sh

set -e  # Exit on error

# Configuration
REPO_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
LOCAL_SITE_NAME="frenchie-allergy-help"
LOCAL_SITE_PATH="$HOME/Local Sites/$LOCAL_SITE_NAME/app/public"
LOCAL_URL="http://frenchie-allergy-help.local"

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}=== Frenchie Care Local Deployment ===${NC}"

# Check if Local by Flywheel site exists
if [ ! -d "$LOCAL_SITE_PATH" ]; then
    echo -e "${RED}Error: Local by Flywheel site not found at: $LOCAL_SITE_PATH${NC}"
    echo "Please ensure Local by Flywheel is installed and the site 'frenchie-allergy-help' exists."
    exit 1
fi

# Navigate to repo directory
cd "$REPO_DIR"

# 1. Pull latest changes from GitHub
echo -e "\n${YELLOW}Step 1: Syncing with GitHub...${NC}"
git pull origin main || {
    echo -e "${RED}Failed to pull from GitHub. Please check your connection and credentials.${NC}"
    exit 1
}

# 2. Copy theme
echo -e "\n${YELLOW}Step 2: Copying theme...${NC}"
THEME_SOURCE="$REPO_DIR/wordpress-upload-ready/themes/frenchie-care"
THEME_DEST="$LOCAL_SITE_PATH/wp-content/themes/"

if [ -d "$THEME_SOURCE" ]; then
    # Remove old theme if exists
    rm -rf "$THEME_DEST/frenchie-care"
    # Copy new theme
    cp -r "$THEME_SOURCE" "$THEME_DEST"
    echo -e "${GREEN}✓ Theme copied successfully${NC}"
else
    echo -e "${RED}Error: Theme source not found at: $THEME_SOURCE${NC}"
    exit 1
fi

# 3. Copy plugins
echo -e "\n${YELLOW}Step 3: Copying plugins...${NC}"
PLUGINS_SOURCE="$REPO_DIR/wordpress-upload-ready/plugins"
PLUGINS_DEST="$LOCAL_SITE_PATH/wp-content/plugins/"

# Copy frenchie-email-automation plugin
if [ -d "$PLUGINS_SOURCE/frenchie-email-automation" ]; then
    rm -rf "$PLUGINS_DEST/frenchie-email-automation"
    cp -r "$PLUGINS_SOURCE/frenchie-email-automation" "$PLUGINS_DEST"
    echo -e "${GREEN}✓ Email automation plugin copied${NC}"
else
    echo -e "${RED}Warning: Email automation plugin not found${NC}"
fi

# Copy frenchie-monetization plugin
if [ -d "$PLUGINS_SOURCE/frenchie-monetization" ]; then
    rm -rf "$PLUGINS_DEST/frenchie-monetization"
    cp -r "$PLUGINS_SOURCE/frenchie-monetization" "$PLUGINS_DEST"
    echo -e "${GREEN}✓ Monetization plugin copied${NC}"
else
    echo -e "${RED}Warning: Monetization plugin not found${NC}"
fi

# 4. Copy automation files
echo -e "\n${YELLOW}Step 4: Setting up automation...${NC}"
AUTOMATION_SOURCE="$REPO_DIR/wordpress-upload-ready/automation/setup-automation.php"
IMPORT_SOURCE="$REPO_DIR/wordpress-upload-ready/import-content"
WP_CONTENT_DEST="$LOCAL_SITE_PATH/wp-content"

if [ -f "$AUTOMATION_SOURCE" ]; then
    cp "$AUTOMATION_SOURCE" "$WP_CONTENT_DEST/"
    echo -e "${GREEN}✓ Automation script copied${NC}"
else
    echo -e "${RED}Error: Automation script not found at: $AUTOMATION_SOURCE${NC}"
    exit 1
fi

if [ -d "$IMPORT_SOURCE" ]; then
    rm -rf "$WP_CONTENT_DEST/import-content"
    cp -r "$IMPORT_SOURCE" "$WP_CONTENT_DEST/"
    echo -e "${GREEN}✓ Import content copied${NC}"
else
    echo -e "${RED}Error: Import content not found at: $IMPORT_SOURCE${NC}"
    exit 1
fi

# 5. Run automation script
echo -e "\n${YELLOW}Step 5: Running automation script...${NC}"
echo "Calling: $LOCAL_URL/wp-content/setup-automation.php"

# Try to run the automation script
response=$(curl -s -o /dev/null -w "%{http_code}" "$LOCAL_URL/wp-content/setup-automation.php" 2>/dev/null || echo "000")

if [ "$response" = "200" ]; then
    echo -e "${GREEN}✓ Automation script executed successfully${NC}"
    
    # Wait a moment for the script to complete
    sleep 2
    
    # Remove the automation script after successful execution
    rm -f "$WP_CONTENT_DEST/setup-automation.php"
    echo -e "${GREEN}✓ Automation script cleaned up${NC}"
elif [ "$response" = "000" ]; then
    echo -e "${RED}Error: Could not connect to local site. Is Local by Flywheel running?${NC}"
    echo "Please start Local by Flywheel and ensure the site is running."
    exit 1
else
    echo -e "${YELLOW}Warning: Automation script returned HTTP $response${NC}"
    echo "You may need to run it manually by visiting: $LOCAL_URL/wp-content/setup-automation.php"
fi

# 6. Set correct permissions
echo -e "\n${YELLOW}Step 6: Setting permissions...${NC}"
chmod -R 755 "$THEME_DEST/frenchie-care"
chmod -R 755 "$PLUGINS_DEST/frenchie-email-automation" 2>/dev/null || true
chmod -R 755 "$PLUGINS_DEST/frenchie-monetization" 2>/dev/null || true

echo -e "\n${GREEN}=== Deployment Complete! ===${NC}"
echo -e "Visit your site at: ${GREEN}$LOCAL_URL${NC}"
echo -e "\n${YELLOW}Next steps:${NC}"
echo "1. Check that the theme is activated in WordPress admin"
echo "2. Ensure required plugins are active:"
echo "   - Contact Form 7"
echo "   - Yoast SEO (or Rank Math)"
echo "   - WP Mail SMTP"
echo "   - Frenchie Email Automation"
echo "   - Frenchie Monetization"
echo "3. Verify permalinks are set to 'Post name'"
echo "4. Check that menus and content have been imported correctly"