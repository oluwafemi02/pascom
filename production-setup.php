<?php
/**
 * Production Setup Script for Frenchie Allergy Help
 * 
 * This script configures WordPress to look exactly like it would in production
 * Upload this to wp-content/ and run it once
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    require_once('../../../wp-load.php');
}

// Set execution time
set_time_limit(300);

echo "<h1>🚀 Production Setup for Frenchie Allergy Help</h1>";
echo "<pre>";

// 1. Configure General Settings
echo "\n📋 Configuring General Settings...\n";
update_option('blogname', 'Frenchie Allergy Help');
update_option('blogdescription', 'Your Complete Guide to French Bulldog Allergies & Care');
update_option('timezone_string', 'Europe/London');
update_option('date_format', 'F j, Y');
update_option('time_format', 'g:i a');
update_option('start_of_week', '1'); // Monday
echo "✅ General settings configured\n";

// 2. Configure Reading Settings
echo "\n📖 Configuring Reading Settings...\n";
$home_page = get_page_by_title('Home');
$blog_page = get_page_by_title('Blog');

if (!$blog_page) {
    // Create blog page if it doesn't exist
    $blog_page_id = wp_insert_post([
        'post_title' => 'Blog',
        'post_content' => '',
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_author' => 1
    ]);
    $blog_page = get_post($blog_page_id);
}

if ($home_page && $blog_page) {
    update_option('show_on_front', 'page');
    update_option('page_on_front', $home_page->ID);
    update_option('page_for_posts', $blog_page->ID);
    echo "✅ Homepage and blog page set\n";
}

update_option('posts_per_page', '9');
update_option('posts_per_rss', '10');
echo "✅ Reading settings configured\n";

// 3. Configure Discussion Settings
echo "\n💬 Configuring Discussion Settings...\n";
update_option('default_comment_status', 'closed'); // Disable comments by default
update_option('default_ping_status', 'closed');
update_option('comment_registration', '1'); // Users must register to comment
update_option('close_comments_for_old_posts', '1');
update_option('close_comments_days_old', '30');
update_option('page_comments', '1');
update_option('comments_per_page', '20');
update_option('comment_moderation', '1');
echo "✅ Discussion settings configured\n";

// 4. Configure Media Settings
echo "\n🖼️ Configuring Media Settings...\n";
update_option('thumbnail_size_w', '150');
update_option('thumbnail_size_h', '150');
update_option('thumbnail_crop', '1');
update_option('medium_size_w', '300');
update_option('medium_size_h', '300');
update_option('large_size_w', '1024');
update_option('large_size_h', '1024');
update_option('uploads_use_yearmonth_folders', '1');
echo "✅ Media settings configured\n";

// 5. Configure Permalinks
echo "\n🔗 Configuring Permalinks...\n";
update_option('permalink_structure', '/%postname%/');
update_option('category_base', 'category');
update_option('tag_base', 'tag');
flush_rewrite_rules();
echo "✅ Permalinks configured\n";

// 6. Create Additional Pages for Production
echo "\n📄 Creating Additional Pages...\n";
$pages_to_create = [
    'Resources' => '<h2>Helpful Resources for French Bulldog Owners</h2><p>Coming soon...</p>',
    'Newsletter' => '<h2>Join Our Newsletter</h2><p>Get weekly tips and updates about French Bulldog care.</p>',
    'Disclaimer' => '<h2>Medical Disclaimer</h2><p>The information on this website is for educational purposes only...</p>',
    'Sitemap' => '<h2>Site Map</h2><p>[Display sitemap here]</p>'
];

foreach ($pages_to_create as $title => $content) {
    if (!get_page_by_title($title)) {
        wp_insert_post([
            'post_title' => $title,
            'post_content' => $content,
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_author' => 1
        ]);
        echo "✅ Page '$title' created\n";
    }
}

// 7. Configure Theme Mods
echo "\n🎨 Configuring Theme Appearance...\n";
set_theme_mod('custom_logo', ''); // You'll need to upload a logo
set_theme_mod('header_text', true);
set_theme_mod('background_color', '#f7fafc');
echo "✅ Theme mods configured\n";

// 8. Create Production-Ready Widgets
echo "\n🔧 Setting Up Widgets...\n";
// This would normally use widget functions, simplified for this script
echo "⚠️  Please manually configure widgets in Appearance → Widgets\n";

// 9. Set Up Sample Menu Items
echo "\n🍔 Updating Menu Structure...\n";
$menu_name = 'Primary Menu';
$menu_exists = wp_get_nav_menu_object($menu_name);

if ($menu_exists) {
    // Add additional menu items
    $pages = ['Resources', 'Newsletter', 'Blog'];
    foreach ($pages as $page_title) {
        $page = get_page_by_title($page_title);
        if ($page) {
            wp_update_nav_menu_item($menu_exists->term_id, 0, [
                'menu-item-title' => $page_title,
                'menu-item-object' => 'page',
                'menu-item-object-id' => $page->ID,
                'menu-item-type' => 'post_type',
                'menu-item-status' => 'publish'
            ]);
        }
    }
    echo "✅ Menu items added\n";
}

// 10. Import Sample Featured Images
echo "\n🖼️ Featured Images Setup...\n";
echo "⚠️  Please add featured images to all posts and pages\n";
echo "   - Use high-quality images (1200x630px minimum)\n";
echo "   - Add alt text for SEO\n";

// 11. Configure SEO-Friendly Settings
echo "\n🔍 SEO Configuration...\n";
update_option('blog_public', '1'); // Allow search engines
echo "✅ Site is visible to search engines\n";

// 12. Performance Recommendations
echo "\n⚡ Performance Setup Required:\n";
echo "   1. Install and activate WP Super Cache\n";
echo "   2. Install and configure Autoptimize\n";
echo "   3. Install an image optimization plugin\n";
echo "   4. Configure lazy loading for images\n";

// 13. Final Production Checklist
echo "\n✅ Production Setup Complete!\n\n";
echo "📋 Final Checklist:\n";
echo "   [ ] Upload a logo (Appearance → Customize → Site Identity)\n";
echo "   [ ] Add featured images to all posts/pages\n";
echo "   [ ] Install Contact Form 7 and add contact form\n";
echo "   [ ] Install and configure caching plugin\n";
echo "   [ ] Add Google Analytics code\n";
echo "   [ ] Configure email SMTP settings\n";
echo "   [ ] Add content to Resources and Newsletter pages\n";
echo "   [ ] Customize widget areas\n";
echo "   [ ] Test all forms and functionality\n";

echo "\n🎉 Your site now has production-ready settings!\n";
echo "</pre>";

// Add styling for better readability
?>
<style>
    body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; padding: 20px; }
    h1 { color: #2C5282; }
    pre { background: #f7fafc; padding: 20px; border-radius: 8px; }
</style>