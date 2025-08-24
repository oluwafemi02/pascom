#!/bin/bash

# configure-wordpress.sh - Configure WordPress settings using WP-CLI
# This script ensures permalinks, plugins, and settings are properly configured

set -e

# Configuration
LOCAL_SITE_NAME="frenchie-allergy-help"
LOCAL_SITE_PATH="$HOME/Local Sites/$LOCAL_SITE_NAME/app/public"

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo -e "${YELLOW}=== Configuring WordPress Settings ===${NC}"

# Check if WP-CLI is available
if ! command -v wp &> /dev/null; then
    echo -e "${RED}WP-CLI is not installed. Some configurations may need to be done manually.${NC}"
    exit 1
fi

cd "$LOCAL_SITE_PATH"

# 1. Set permalinks to "Post name"
echo -e "\n${YELLOW}Setting permalinks...${NC}"
wp rewrite structure '/%postname%/' --path="$LOCAL_SITE_PATH"
wp rewrite flush --path="$LOCAL_SITE_PATH"
echo -e "${GREEN}✓ Permalinks set to 'Post name'${NC}"

# 2. Activate theme
echo -e "\n${YELLOW}Activating Frenchie Care theme...${NC}"
wp theme activate frenchie-care --path="$LOCAL_SITE_PATH" 2>/dev/null || {
    echo -e "${RED}Could not activate theme. Please activate manually.${NC}"
}

# 3. Activate required plugins
echo -e "\n${YELLOW}Activating plugins...${NC}"
plugins=(
    "frenchie-email-automation"
    "frenchie-monetization"
    "contact-form-7"
    "wordpress-seo"  # Yoast SEO
    "wp-mail-smtp"
)

for plugin in "${plugins[@]}"; do
    if wp plugin is-installed "$plugin" --path="$LOCAL_SITE_PATH" 2>/dev/null; then
        wp plugin activate "$plugin" --path="$LOCAL_SITE_PATH" 2>/dev/null && \
            echo -e "${GREEN}✓ Activated: $plugin${NC}" || \
            echo -e "${YELLOW}! Could not activate: $plugin${NC}"
    else
        echo -e "${YELLOW}! Plugin not installed: $plugin${NC}"
    fi
done

# 4. Create default menu locations
echo -e "\n${YELLOW}Setting up menus...${NC}"
wp menu create "Primary Menu" --path="$LOCAL_SITE_PATH" 2>/dev/null || true
wp menu location assign "Primary Menu" primary --path="$LOCAL_SITE_PATH" 2>/dev/null || true
echo -e "${GREEN}✓ Menu locations configured${NC}"

# 5. Set basic options
echo -e "\n${YELLOW}Configuring basic settings...${NC}"
wp option update blogdescription "Expert care and solutions for French Bulldogs with allergies" --path="$LOCAL_SITE_PATH"
wp option update default_comment_status closed --path="$LOCAL_SITE_PATH"
wp option update default_ping_status closed --path="$LOCAL_SITE_PATH"
echo -e "${GREEN}✓ Basic settings configured${NC}"

echo -e "\n${GREEN}=== WordPress Configuration Complete ===${NC}"