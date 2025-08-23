<?php
/**
 * Create Homepage Script for Frenchie Allergy Help
 * Run this in wp-content/ to create a proper homepage
 */

// Load WordPress
if (!defined('ABSPATH')) {
    require_once('../../../wp-load.php');
}

echo "<h1>🏠 Creating Proper Homepage for Frenchie Allergy Help</h1>";
echo "<pre>";

// Homepage content with sections
$homepage_content = '
<!-- Hero Section -->
<div class="hero-section" style="background: linear-gradient(135deg, #2C5282 0%, #2D3748 100%); color: white; padding: 80px 0; text-align: center; margin: -40px -40px 40px -40px;">
    <div class="container">
        <h1 style="font-size: 3em; margin-bottom: 20px;">Welcome to Frenchie Allergy Help</h1>
        <p style="font-size: 1.3em; margin-bottom: 30px;">Your trusted resource for managing French Bulldog allergies and providing the best care for your furry friend.</p>
        <a href="/start-here/" class="button" style="background: #ED8936; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">Start Here →</a>
    </div>
</div>

<!-- Introduction Section -->
<div class="intro-section" style="padding: 60px 0;">
    <div class="container">
        <h2 style="text-align: center; margin-bottom: 40px;">Helping French Bulldogs Live Their Best Lives</h2>
        <p style="font-size: 1.1em; line-height: 1.8; text-align: center; max-width: 800px; margin: 0 auto;">
            If your French Bulldog suffers from allergies, you\'re not alone. Nearly 20% of Frenchies experience some form of allergic reaction. 
            We\'re here to help you identify symptoms, find effective treatments, and prevent future flare-ups.
        </p>
    </div>
</div>

<!-- Features Grid -->
<div class="features-section" style="background: #F7FAFC; padding: 60px 0; margin: 0 -40px;">
    <div class="container">
        <h2 style="text-align: center; margin-bottom: 40px;">What You\'ll Find Here</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; max-width: 1000px; margin: 0 auto;">
            
            <div class="feature-card" style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h3 style="color: #2C5282; margin-bottom: 15px;">🔍 Allergy Identification</h3>
                <p>Learn to recognize the signs and symptoms of different types of allergies in French Bulldogs.</p>
                <a href="/category/allergies/" style="color: #ED8936;">Explore Allergies →</a>
            </div>
            
            <div class="feature-card" style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h3 style="color: #2C5282; margin-bottom: 15px;">🥗 Nutrition Guides</h3>
                <p>Discover the best hypoallergenic diets and foods to help manage your Frenchie\'s allergies.</p>
                <a href="/category/nutrition/" style="color: #ED8936;">Nutrition Tips →</a>
            </div>
            
            <div class="feature-card" style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h3 style="color: #2C5282; margin-bottom: 15px;">💊 Treatment Options</h3>
                <p>Explore various treatment methods from natural remedies to veterinary solutions.</p>
                <a href="/category/health/" style="color: #ED8936;">Health Resources →</a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Articles Section -->
<div class="articles-section" style="padding: 60px 0;">
    <div class="container">
        <h2 style="text-align: center; margin-bottom: 40px;">Latest Articles</h2>
        [recent_posts limit="3" show_excerpt="true"]
    </div>
</div>

<!-- Newsletter Section -->
<div class="newsletter-section" style="background: #2C5282; color: white; padding: 60px 0; margin: 0 -40px; text-align: center;">
    <div class="container">
        <h2 style="margin-bottom: 20px;">Get Your Free Frenchie Allergy Guide</h2>
        <p style="font-size: 1.1em; margin-bottom: 30px;">Join thousands of Frenchie parents who receive our weekly tips and exclusive content.</p>
        [frenchie_subscribe lead_magnet="free-guide" show_name="true"]
    </div>
</div>

<!-- Resource Cards -->
<div class="resources-section" style="padding: 60px 0;">
    <div class="container">
        <h2 style="text-align: center; margin-bottom: 40px;">Essential Resources</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; max-width: 900px; margin: 0 auto;">
            
            <div class="resource-card" style="text-align: center; padding: 30px; background: #F7FAFC; border-radius: 8px;">
                <div style="font-size: 3em; margin-bottom: 20px;">📋</div>
                <h3>Allergy Checklist</h3>
                <p>Download our comprehensive checklist to track your Frenchie\'s symptoms.</p>
                <a href="/downloads/allergy-checklist/" class="button" style="background: #48BB78; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 15px;">Download Free</a>
            </div>
            
            <div class="resource-card" style="text-align: center; padding: 30px; background: #F7FAFC; border-radius: 8px;">
                <div style="font-size: 3em; margin-bottom: 20px;">🎯</div>
                <h3>Elimination Diet Guide</h3>
                <p>Step-by-step guide to identifying food allergies through elimination diets.</p>
                <a href="/downloads/elimination-diet/" class="button" style="background: #48BB78; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 15px;">Get Guide</a>
            </div>
            
            <div class="resource-card" style="text-align: center; padding: 30px; background: #F7FAFC; border-radius: 8px;">
                <div style="font-size: 3em; margin-bottom: 20px;">💼</div>
                <h3>Complete Toolkit</h3>
                <p>Premium resources including meal plans, tracking sheets, and vet guides.</p>
                <a href="/frenchie-allergy-toolkit/" class="button" style="background: #ED8936; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 15px;">Learn More</a>
            </div>
        </div>
    </div>
</div>

<!-- Trust Section -->
<div class="trust-section" style="background: #F7FAFC; padding: 60px 0; margin: 0 -40px;">
    <div class="container" style="text-align: center;">
        <h2 style="margin-bottom: 40px;">Why Trust Frenchie Allergy Help?</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; max-width: 800px; margin: 0 auto;">
            <div>
                <div style="font-size: 2.5em; color: #2C5282; margin-bottom: 10px;">5+</div>
                <p><strong>Years of Experience</strong><br>Helping Frenchie owners</p>
            </div>
            <div>
                <div style="font-size: 2.5em; color: #2C5282; margin-bottom: 10px;">1000+</div>
                <p><strong>Happy Readers</strong><br>Trust our advice</p>
            </div>
            <div>
                <div style="font-size: 2.5em; color: #2C5282; margin-bottom: 10px;">50+</div>
                <p><strong>Expert Articles</strong><br>Research-backed content</p>
            </div>
            <div>
                <div style="font-size: 2.5em; color: #2C5282; margin-bottom: 10px;">24/7</div>
                <p><strong>Available Resources</strong><br>Access anytime</p>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div class="cta-section" style="padding: 60px 0; text-align: center;">
    <div class="container">
        <h2 style="margin-bottom: 20px;">Ready to Help Your Frenchie Feel Better?</h2>
        <p style="font-size: 1.1em; margin-bottom: 30px;">Start with our comprehensive guide to understanding and managing French Bulldog allergies.</p>
        <a href="/start-here/" class="button" style="background: #2C5282; color: white; padding: 15px 40px; text-decoration: none; border-radius: 5px; display: inline-block; font-size: 1.1em;">Get Started Now →</a>
    </div>
</div>

<style>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.button:hover {
    opacity: 0.9;
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

.feature-card:hover, .resource-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    transition: all 0.3s ease;
}

@media (max-width: 768px) {
    .hero-section h1 {
        font-size: 2em !important;
    }
    .hero-section {
        padding: 40px 0 !important;
    }
}
</style>
';

// Check if a Home page already exists
$existing_home = get_page_by_title('Home');
if ($existing_home) {
    // Update existing page
    $page_id = wp_update_post([
        'ID' => $existing_home->ID,
        'post_content' => $homepage_content,
        'post_status' => 'publish'
    ]);
    echo "✅ Updated existing Home page with proper content\n";
} else {
    // Create new page
    $page_id = wp_insert_post([
        'post_title' => 'Home',
        'post_content' => $homepage_content,
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_name' => 'home'
    ]);
    echo "✅ Created new Home page with proper content\n";
}

// Set as front page
update_option('show_on_front', 'page');
update_option('page_on_front', $page_id);
echo "✅ Set Home page as front page\n";

// Create Blog page if it doesn't exist
$blog_page = get_page_by_title('Blog');
if (!$blog_page) {
    $blog_id = wp_insert_post([
        'post_title' => 'Blog',
        'post_content' => '',
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_name' => 'blog'
    ]);
    update_option('page_for_posts', $blog_id);
    echo "✅ Created Blog page for posts\n";
} else {
    update_option('page_for_posts', $blog_page->ID);
    echo "✅ Set existing Blog page for posts\n";
}

// Update permalinks
update_option('permalink_structure', '/%postname%/');
flush_rewrite_rules();
echo "✅ Updated permalinks structure\n";

echo "\n🎉 Homepage creation complete!\n\n";
echo "Next steps:\n";
echo "1. Visit your homepage to see the new layout\n";
echo "2. Clear any caches if you have caching plugins\n";
echo "3. Customize colors and content as needed\n";
echo "4. Add featured images to your posts\n";
echo "5. Configure widgets in Appearance → Widgets\n";

echo "</pre>";
?>