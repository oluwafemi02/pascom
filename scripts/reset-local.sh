#!/bin/bash

# reset-local.sh - Completely reset and re-import WordPress content from repo
# Usage: ./scripts/reset-local.sh

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
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${RED}=== Frenchie Care Local Site Reset ===${NC}"
echo -e "${YELLOW}WARNING: This will delete ALL content and reset your local WordPress installation!${NC}"
echo -n "Are you sure you want to continue? (yes/no): "
read -r confirmation

if [ "$confirmation" != "yes" ]; then
    echo "Reset cancelled."
    exit 0
fi

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

# 2. Clean up WordPress content
echo -e "\n${YELLOW}Step 2: Cleaning WordPress installation...${NC}"

# Remove all themes except default ones
echo "Removing existing themes..."
find "$LOCAL_SITE_PATH/wp-content/themes" -maxdepth 1 -type d \
    ! -name "themes" \
    ! -name "twenty*" \
    -exec rm -rf {} + 2>/dev/null || true

# Remove all non-default plugins
echo "Removing existing plugins..."
find "$LOCAL_SITE_PATH/wp-content/plugins" -maxdepth 1 -type d \
    ! -name "plugins" \
    ! -name "akismet" \
    ! -name "hello.php" \
    -exec rm -rf {} + 2>/dev/null || true

# Remove uploads
echo "Cleaning uploads directory..."
rm -rf "$LOCAL_SITE_PATH/wp-content/uploads/"*

# 3. Create database reset script
echo -e "\n${YELLOW}Step 3: Creating database reset script...${NC}"
cat > "$LOCAL_SITE_PATH/wp-content/reset-database.php" << 'EOF'
<?php
// Load WordPress
require_once('../wp-load.php');

// Check if script is accessed directly
if (!defined('ABSPATH')) {
    die('Direct access not allowed');
}

global $wpdb;

echo "<h2>Database Reset Started...</h2>";

// Delete all posts, pages, and custom post types
$wpdb->query("DELETE FROM {$wpdb->posts} WHERE post_type NOT IN ('nav_menu_item')");
$wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE post_id NOT IN (SELECT ID FROM {$wpdb->posts})");
echo "✓ Posts and pages deleted<br>";

// Delete all terms and taxonomies (except uncategorized)
$wpdb->query("DELETE FROM {$wpdb->terms} WHERE term_id > 1");
$wpdb->query("DELETE FROM {$wpdb->term_taxonomy} WHERE term_id > 1");
$wpdb->query("DELETE FROM {$wpdb->term_relationships} WHERE term_taxonomy_id NOT IN (SELECT term_taxonomy_id FROM {$wpdb->term_taxonomy})");
echo "✓ Categories and tags deleted<br>";

// Delete all comments
$wpdb->query("TRUNCATE TABLE {$wpdb->comments}");
$wpdb->query("TRUNCATE TABLE {$wpdb->commentmeta}");
echo "✓ Comments deleted<br>";

// Reset options (keep essential ones)
$essential_options = array(
    'siteurl', 'home', 'blogname', 'blogdescription', 'users_can_register',
    'admin_email', 'start_of_week', 'use_balanceTags', 'use_smilies',
    'require_name_email', 'comments_notify', 'posts_per_rss', 'rss_use_excerpt',
    'mailserver_url', 'mailserver_login', 'mailserver_pass', 'mailserver_port',
    'default_category', 'default_comment_status', 'default_ping_status',
    'default_pingback_flag', 'posts_per_page', 'date_format', 'time_format',
    'links_updated_date_format', 'comment_moderation', 'moderation_notify',
    'permalink_structure', 'rewrite_rules', 'hack_file', 'blog_charset',
    'moderation_keys', 'active_plugins', 'category_base', 'ping_sites',
    'comment_max_links', 'gmt_offset', 'default_email_category', 'recently_edited',
    'template', 'stylesheet', 'comment_registration', 'html_type',
    'use_trackback', 'default_role', 'db_version', 'uploads_use_yearmonth_folders',
    'upload_path', 'blog_public', 'default_link_category', 'show_on_front',
    'tag_base', 'show_avatars', 'avatar_rating', 'upload_url_path', 'thumbnail_size_w',
    'thumbnail_size_h', 'thumbnail_crop', 'medium_size_w', 'medium_size_h',
    'avatar_default', 'large_size_w', 'large_size_h', 'image_default_link_type',
    'image_default_size', 'image_default_align', 'close_comments_for_old_posts',
    'close_comments_days_old', 'thread_comments', 'thread_comments_depth',
    'page_comments', 'comments_per_page', 'default_comments_page', 'comment_order',
    'sticky_posts', 'widget_categories', 'widget_text', 'widget_rss',
    'uninstall_plugins', 'timezone_string', 'page_for_posts', 'page_on_front',
    'initial_db_version', 'user_roles', 'widget_search', 'widget_recent-posts',
    'widget_recent-comments', 'widget_archives', 'widget_meta', 'sidebars_widgets',
    'widget_calendar', 'widget_nav_menu', 'widget_pages', 'widget_tag_cloud',
    'widget_custom_html', 'widget_media_audio', 'widget_media_image',
    'widget_media_gallery', 'widget_media_video', 'widget_audio', 'widget_image',
    'widget_gallery', 'widget_video'
);

// Keep only essential options
$options_list = implode("','", $essential_options);
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name NOT IN ('{$options_list}') AND option_name NOT LIKE '\_%'");
echo "✓ Options reset<br>";

// Delete all user meta except essential
$wpdb->query("DELETE FROM {$wpdb->usermeta} WHERE meta_key NOT IN ('nickname', 'first_name', 'last_name', 'description', 'rich_editing', 'syntax_highlighting', 'comment_shortcuts', 'admin_color', 'use_ssl', 'show_admin_bar_front', 'locale', 'wp_capabilities', 'wp_user_level', 'dismissed_wp_pointers')");
echo "✓ User meta cleaned<br>";

// Set permalinks to post name
update_option('permalink_structure', '/%postname%/');
echo "✓ Permalinks set to 'Post name'<br>";

// Flush rewrite rules
flush_rewrite_rules();

echo "<h2>Database reset complete!</h2>";
echo "<p>The database has been cleaned and is ready for fresh content import.</p>";
EOF

# Run the database reset
echo -e "${BLUE}Resetting WordPress database...${NC}"
response=$(curl -s "$LOCAL_URL/wp-content/reset-database.php" 2>/dev/null)
if [[ $response == *"Database reset complete"* ]]; then
    echo -e "${GREEN}✓ Database reset successfully${NC}"
    rm -f "$LOCAL_SITE_PATH/wp-content/reset-database.php"
else
    echo -e "${YELLOW}Warning: Could not verify database reset. You may need to run it manually.${NC}"
fi

# 4. Now run the regular deployment
echo -e "\n${YELLOW}Step 4: Running deployment...${NC}"
"$REPO_DIR/scripts/deploy-local.sh"

echo -e "\n${GREEN}=== Reset Complete! ===${NC}"
echo -e "Your local WordPress site has been completely reset and fresh content has been imported."
echo -e "Visit your site at: ${GREEN}$LOCAL_URL${NC}"