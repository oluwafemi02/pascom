<?php
/**
 * Frenchie Allergy Help - WordPress Automation Setup Script
 * 
 * This script automates the setup of content, pages, posts, menus, and widgets
 * Run this from your WordPress root directory or upload to wp-content/mu-plugins/
 * 
 * Usage: 
 * 1. Upload this file to your WordPress installation
 * 2. Access it via: http://your-site.com/wp-content/setup-automation.php
 * 3. Or run via WP-CLI: wp eval-file wp-content/setup-automation.php
 */

// Load WordPress
if (!defined('ABSPATH')) {
    require_once('../../../wp-load.php');
}

class FrenchieAllergySetup {
    
    private $content_path;
    private $created_pages = [];
    private $created_posts = [];
    
    public function __construct() {
        $this->content_path = dirname(__FILE__) . '/../../site-content/';
    }
    
    /**
     * Main setup function
     */
    public function run_setup() {
        echo "<h1>🐾 Frenchie Allergy Help - Automated Setup</h1>";
        echo "<pre>";
        
        // Step 1: Create Categories
        $this->create_categories();
        
        // Step 2: Create Pages
        $this->create_pages();
        
        // Step 3: Create Posts
        $this->create_posts();
        
        // Step 4: Setup Menus
        $this->setup_menus();
        
        // Step 5: Configure Settings
        $this->configure_settings();
        
        // Step 6: Setup Widgets
        $this->setup_widgets();
        
        echo "\n✅ Setup Complete!</pre>";
    }
    
    /**
     * Create post categories
     */
    private function create_categories() {
        echo "\n📁 Creating Categories...\n";
        
        $categories = [
            'Food Allergies' => 'food-allergies',
            'Environmental Allergies' => 'environmental-allergies',
            'Skin Care' => 'skin-care',
            'Product Reviews' => 'product-reviews',
            'Treatment Guides' => 'treatment-guides'
        ];
        
        foreach ($categories as $name => $slug) {
            $term = term_exists($name, 'category');
            if (!$term) {
                $result = wp_insert_term($name, 'category', ['slug' => $slug]);
                if (!is_wp_error($result)) {
                    echo "✓ Created category: $name\n";
                }
            } else {
                echo "- Category already exists: $name\n";
            }
        }
    }
    
    /**
     * Create WordPress pages from HTML files
     */
    private function create_pages() {
        echo "\n📄 Creating Pages...\n";
        
        $pages = [
            'about' => [
                'title' => 'About',
                'file' => 'pages/about.html'
            ],
            'start-here' => [
                'title' => 'Start Here',
                'file' => 'pages/start-here.html'
            ],
            'medical-disclaimer' => [
                'title' => 'Medical Disclaimer',
                'file' => 'pages/medical-disclaimer.html'
            ],
            'privacy-policy' => [
                'title' => 'Privacy Policy',
                'file' => '../legal-pages/privacy-policy.html'
            ],
            'affiliate-disclosure' => [
                'title' => 'Affiliate Disclosure',
                'file' => '../legal-pages/affiliate-disclosure.html'
            ],
            'contact' => [
                'title' => 'Contact',
                'content' => $this->get_contact_form_content()
            ]
        ];
        
        foreach ($pages as $slug => $page_data) {
            // Check if page already exists
            $existing = get_page_by_path($slug);
            if ($existing) {
                echo "- Page already exists: {$page_data['title']}\n";
                $this->created_pages[$slug] = $existing->ID;
                continue;
            }
            
            // Get content
            if (isset($page_data['content'])) {
                $content = $page_data['content'];
            } else {
                $file_path = $this->content_path . $page_data['file'];
                if (file_exists($file_path)) {
                    $content = file_get_contents($file_path);
                    // Extract body content if full HTML
                    if (preg_match('/<body[^>]*>(.*?)<\/body>/is', $content, $matches)) {
                        $content = $matches[1];
                    }
                } else {
                    echo "⚠ File not found: $file_path\n";
                    continue;
                }
            }
            
            // Create page
            $page_id = wp_insert_post([
                'post_title' => $page_data['title'],
                'post_content' => $content,
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_name' => $slug
            ]);
            
            if ($page_id && !is_wp_error($page_id)) {
                echo "✓ Created page: {$page_data['title']}\n";
                $this->created_pages[$slug] = $page_id;
            }
        }
    }
    
    /**
     * Create blog posts from article files
     */
    private function create_posts() {
        echo "\n📝 Creating Posts...\n";
        
        $posts = [
            [
                'title' => 'French Bulldog Food Allergies: Complete Guide',
                'file' => 'articles/food-allergies-guide.html',
                'categories' => ['Food Allergies', 'Treatment Guides'],
                'slug' => 'french-bulldog-food-allergies-guide'
            ],
            [
                'title' => 'French Bulldog Seasonal Allergies: Management Guide',
                'file' => 'articles/seasonal-allergies-guide.html',
                'categories' => ['Environmental Allergies', 'Treatment Guides'],
                'slug' => 'french-bulldog-seasonal-allergies-guide'
            ]
        ];
        
        foreach ($posts as $post_data) {
            // Check if post exists
            $existing = get_page_by_path($post_data['slug'], OBJECT, 'post');
            if ($existing) {
                echo "- Post already exists: {$post_data['title']}\n";
                continue;
            }
            
            // Get content
            $file_path = $this->content_path . $post_data['file'];
            if (!file_exists($file_path)) {
                echo "⚠ File not found: $file_path\n";
                continue;
            }
            
            $content = file_get_contents($file_path);
            // Extract body content if full HTML
            if (preg_match('/<body[^>]*>(.*?)<\/body>/is', $content, $matches)) {
                $content = $matches[1];
            }
            
            // Get category IDs
            $category_ids = [];
            foreach ($post_data['categories'] as $cat_name) {
                $term = get_term_by('name', $cat_name, 'category');
                if ($term) {
                    $category_ids[] = $term->term_id;
                }
            }
            
            // Create post
            $post_id = wp_insert_post([
                'post_title' => $post_data['title'],
                'post_content' => $content,
                'post_status' => 'publish',
                'post_type' => 'post',
                'post_name' => $post_data['slug'],
                'post_category' => $category_ids
            ]);
            
            if ($post_id && !is_wp_error($post_id)) {
                echo "✓ Created post: {$post_data['title']}\n";
                $this->created_posts[] = $post_id;
            }
        }
    }
    
    /**
     * Setup WordPress menus
     */
    private function setup_menus() {
        echo "\n🍔 Setting Up Menus...\n";
        
        // Register menu locations if theme doesn't have them
        register_nav_menus([
            'primary' => 'Primary Menu',
            'footer' => 'Footer Menu'
        ]);
        
        // Create Primary Menu
        $primary_menu_name = 'Primary Menu';
        $primary_menu_exists = wp_get_nav_menu_object($primary_menu_name);
        
        if (!$primary_menu_exists) {
            $primary_menu_id = wp_create_nav_menu($primary_menu_name);
            
            if (!is_wp_error($primary_menu_id)) {
                // Add menu items
                wp_update_nav_menu_item($primary_menu_id, 0, [
                    'menu-item-title' => 'Home',
                    'menu-item-url' => home_url('/'),
                    'menu-item-status' => 'publish'
                ]);
                
                if (isset($this->created_pages['start-here'])) {
                    wp_update_nav_menu_item($primary_menu_id, 0, [
                        'menu-item-title' => 'Start Here',
                        'menu-item-object-id' => $this->created_pages['start-here'],
                        'menu-item-object' => 'page',
                        'menu-item-type' => 'post_type',
                        'menu-item-status' => 'publish'
                    ]);
                }
                
                if (isset($this->created_pages['about'])) {
                    wp_update_nav_menu_item($primary_menu_id, 0, [
                        'menu-item-title' => 'About',
                        'menu-item-object-id' => $this->created_pages['about'],
                        'menu-item-object' => 'page',
                        'menu-item-type' => 'post_type',
                        'menu-item-status' => 'publish'
                    ]);
                }
                
                // Assign menu to location
                set_theme_mod('nav_menu_locations', ['primary' => $primary_menu_id]);
                echo "✓ Created Primary Menu\n";
            }
        } else {
            echo "- Primary Menu already exists\n";
        }
        
        // Create Footer Menu
        $footer_menu_name = 'Footer Menu';
        $footer_menu_exists = wp_get_nav_menu_object($footer_menu_name);
        
        if (!$footer_menu_exists) {
            $footer_menu_id = wp_create_nav_menu($footer_menu_name);
            
            if (!is_wp_error($footer_menu_id)) {
                // Add legal pages to footer
                $footer_pages = ['privacy-policy', 'affiliate-disclosure', 'medical-disclaimer', 'contact'];
                
                foreach ($footer_pages as $page_slug) {
                    if (isset($this->created_pages[$page_slug])) {
                        wp_update_nav_menu_item($footer_menu_id, 0, [
                            'menu-item-object-id' => $this->created_pages[$page_slug],
                            'menu-item-object' => 'page',
                            'menu-item-type' => 'post_type',
                            'menu-item-status' => 'publish'
                        ]);
                    }
                }
                
                echo "✓ Created Footer Menu\n";
            }
        } else {
            echo "- Footer Menu already exists\n";
        }
    }
    
    /**
     * Configure WordPress settings
     */
    private function configure_settings() {
        echo "\n⚙️ Configuring Settings...\n";
        
        // Set permalink structure
        update_option('permalink_structure', '/%postname%/');
        echo "✓ Set permalink structure to post name\n";
        
        // Disable comments on pages
        update_option('default_comment_status', 'closed');
        echo "✓ Disabled comments by default\n";
        
        // Set timezone
        update_option('timezone_string', 'America/New_York');
        echo "✓ Set timezone\n";
        
        // Create homepage
        $homepage_content = $this->get_homepage_content();
        $homepage = get_page_by_title('Home');
        
        if (!$homepage) {
            $homepage_id = wp_insert_post([
                'post_title' => 'Home',
                'post_content' => $homepage_content,
                'post_status' => 'publish',
                'post_type' => 'page'
            ]);
            
            if ($homepage_id && !is_wp_error($homepage_id)) {
                update_option('show_on_front', 'page');
                update_option('page_on_front', $homepage_id);
                echo "✓ Created and set homepage\n";
            }
        }
        
        // Create blog page
        $blog_page = get_page_by_title('Blog');
        if (!$blog_page) {
            $blog_id = wp_insert_post([
                'post_title' => 'Blog',
                'post_content' => '',
                'post_status' => 'publish',
                'post_type' => 'page'
            ]);
            
            if ($blog_id && !is_wp_error($blog_id)) {
                update_option('page_for_posts', $blog_id);
                echo "✓ Created blog page\n";
            }
        }
    }
    
    /**
     * Setup sidebar widgets
     */
    private function setup_widgets() {
        echo "\n🔧 Setting Up Widgets...\n";
        
        // This would need to be customized based on your theme's widget areas
        // For now, we'll just output instructions
        echo "ℹ Please manually configure widgets in Appearance → Widgets\n";
        echo "  Recommended widgets:\n";
        echo "  - Email Signup (Frenchie Subscribe)\n";
        echo "  - Recent Posts\n";
        echo "  - Categories\n";
    }
    
    /**
     * Get contact form content
     */
    private function get_contact_form_content() {
        return '
        <h2>Get in Touch</h2>
        <p>Have questions about your Frenchie\'s allergies? We\'re here to help!</p>
        
        <!-- Install Contact Form 7 and use shortcode: [contact-form-7 id="YOUR_FORM_ID" title="Contact form 1"] -->
        <p><em>Contact form will appear here after Contact Form 7 plugin installation.</em></p>
        
        <h3>Quick Response Times</h3>
        <p>We typically respond within 24-48 hours. For urgent allergy concerns, please consult your veterinarian immediately.</p>
        ';
    }
    
    /**
     * Get homepage content
     */
    private function get_homepage_content() {
        return '
        <div class="hero-section">
            <h1>Welcome to Frenchie Allergy Help</h1>
            <p class="lead">Your trusted resource for managing French Bulldog allergies naturally and effectively.</p>
            
            <div class="cta-buttons">
                <a href="/start-here/" class="button button-primary">Start Here</a>
                <a href="/blog/" class="button button-secondary">Browse Articles</a>
            </div>
        </div>
        
        <section class="features">
            <h2>How We Can Help Your Frenchie</h2>
            
            <div class="feature-grid">
                <div class="feature">
                    <h3>🎯 Identify Allergies</h3>
                    <p>Learn to recognize allergy symptoms and distinguish them from other conditions.</p>
                </div>
                
                <div class="feature">
                    <h3>🥗 Nutrition Guides</h3>
                    <p>Discover the best hypoallergenic foods and elimination diet strategies.</p>
                </div>
                
                <div class="feature">
                    <h3>🏠 Home Remedies</h3>
                    <p>Natural solutions to soothe your Frenchie\'s allergy symptoms safely.</p>
                </div>
                
                <div class="feature">
                    <h3>📋 Action Plans</h3>
                    <p>Step-by-step guides for managing both acute reactions and long-term care.</p>
                </div>
            </div>
        </section>
        
        <section class="recent-posts">
            <h2>Latest Articles</h2>
            <!-- Recent posts will be displayed here dynamically -->
            [recent_posts number="3"]
        </section>
        
        <section class="newsletter-signup">
            <h2>Get Our Free Allergy Care Toolkit</h2>
            <p>Join thousands of Frenchie parents who are successfully managing their pup\'s allergies.</p>
            <!-- Add your email signup form here -->
        </section>
        ';
    }
}

// Run the setup
if (!defined('WP_CLI') || !WP_CLI) {
    $setup = new FrenchieAllergySetup();
    $setup->run_setup();
}