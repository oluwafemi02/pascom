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

// Load WordPress - FIXED PATH
if (!defined('ABSPATH')) {
    require_once('../wp-load.php');  // Changed from ../../../wp-load.php
}

class FrenchieAllergySetup {
    
    private $content_path;
    private $created_pages = [];
    private $created_posts = [];
    
    public function __construct() {
        // Updated path to look for import-content in the same directory
        $this->content_path = dirname(__FILE__) . '/import-content/';
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
        
        // Step 6: Import Widgets
        $this->setup_widgets();
        
        echo "\n✅ <strong>Setup completed successfully!</strong>\n";
        echo "\nNext steps:\n";
        echo "1. Delete this setup file for security\n";
        echo "2. Visit your site to see the changes\n";
        echo "3. Customize content as needed\n";
        echo "</pre>";
    }
    
    /**
     * Create categories
     */
    private function create_categories() {
        echo "\n<strong>Creating categories...</strong>\n";
        
        $categories = [
            'Allergies' => 'Information about French Bulldog allergies',
            'Health' => 'General health tips for French Bulldogs',
            'Nutrition' => 'Diet and nutrition advice',
            'Grooming' => 'Grooming tips and techniques',
            'Lifestyle' => 'Living with a French Bulldog'
        ];
        
        foreach ($categories as $name => $description) {
            $result = wp_insert_term($name, 'category', [
                'description' => $description,
                'slug' => sanitize_title($name)
            ]);
            
            if (is_wp_error($result)) {
                echo "⚠️  Category '$name' already exists or error: " . $result->get_error_message() . "\n";
            } else {
                echo "✅ Category '$name' created\n";
            }
        }
    }
    
    /**
     * Create pages from HTML files
     */
    private function create_pages() {
        echo "\n<strong>Creating pages...</strong>\n";
        
        $pages = [
            'home' => ['title' => 'Home', 'template' => 'page-home.php'],
            'about' => ['title' => 'About', 'template' => ''],
            'start-here' => ['title' => 'Start Here', 'template' => ''],
            'contact' => ['title' => 'Contact', 'template' => ''],
            'privacy-policy' => ['title' => 'Privacy Policy', 'template' => ''],
            'affiliate-disclosure' => ['title' => 'Affiliate Disclosure', 'template' => '']
        ];
        
        foreach ($pages as $slug => $page_data) {
            $content = $this->get_content_from_file($slug . '.html');
            
            // For legal pages, check in legal-pages directory
            if (in_array($slug, ['privacy-policy', 'affiliate-disclosure']) && !$content) {
                $content = $this->get_legal_content($slug . '.html');
            }
            
            if ($content) {
                $page_id = wp_insert_post([
                    'post_title' => $page_data['title'],
                    'post_content' => $content,
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'post_name' => $slug,
                    'page_template' => $page_data['template']
                ]);
                
                if ($page_id) {
                    $this->created_pages[$slug] = $page_id;
                    echo "✅ Page '{$page_data['title']}' created\n";
                } else {
                    echo "❌ Failed to create page '{$page_data['title']}'\n";
                }
            } else {
                echo "⚠️  No content file found for '{$page_data['title']}'\n";
            }
        }
    }
    
    /**
     * Create blog posts
     */
    private function create_posts() {
        echo "\n<strong>Creating posts...</strong>\n";
        
        $posts = [
            [
                'title' => 'Understanding French Bulldog Allergies: A Complete Guide',
                'slug' => 'understanding-french-bulldog-allergies',
                'file' => 'understanding-french-bulldog-allergies.html',
                'categories' => ['Allergies', 'Health']
            ],
            [
                'title' => 'Common Food Allergies in French Bulldogs',
                'slug' => 'common-food-allergies-french-bulldogs',
                'file' => 'common-food-allergies-french-bulldogs.html',
                'categories' => ['Allergies', 'Nutrition']
            ]
        ];
        
        foreach ($posts as $post_data) {
            $content = $this->get_content_from_file($post_data['file']);
            
            if ($content) {
                // Get category IDs
                $category_ids = [];
                foreach ($post_data['categories'] as $cat_name) {
                    $term = get_term_by('name', $cat_name, 'category');
                    if ($term) {
                        $category_ids[] = $term->term_id;
                    }
                }
                
                $post_id = wp_insert_post([
                    'post_title' => $post_data['title'],
                    'post_content' => $content,
                    'post_status' => 'publish',
                    'post_type' => 'post',
                    'post_name' => $post_data['slug'],
                    'post_category' => $category_ids
                ]);
                
                if ($post_id) {
                    $this->created_posts[] = $post_id;
                    echo "✅ Post '{$post_data['title']}' created\n";
                } else {
                    echo "❌ Failed to create post '{$post_data['title']}'\n";
                }
            } else {
                echo "⚠️  No content file found for post '{$post_data['title']}'\n";
            }
        }
    }
    
    /**
     * Setup menus
     */
    private function setup_menus() {
        echo "\n<strong>Setting up menus...</strong>\n";
        
        // Create Primary Menu
        $primary_menu_id = wp_create_nav_menu('Primary Menu');
        if (!is_wp_error($primary_menu_id)) {
            echo "✅ Primary menu created\n";
            
            // Add menu items
            $menu_items = [
                'Home' => isset($this->created_pages['home']) ? $this->created_pages['home'] : 0,
                'About' => isset($this->created_pages['about']) ? $this->created_pages['about'] : 0,
                'Start Here' => isset($this->created_pages['start-here']) ? $this->created_pages['start-here'] : 0,
                'Contact' => isset($this->created_pages['contact']) ? $this->created_pages['contact'] : 0
            ];
            
            $position = 1;
            foreach ($menu_items as $title => $page_id) {
                if ($page_id) {
                    wp_update_nav_menu_item($primary_menu_id, 0, [
                        'menu-item-title' => $title,
                        'menu-item-object' => 'page',
                        'menu-item-object-id' => $page_id,
                        'menu-item-type' => 'post_type',
                        'menu-item-status' => 'publish',
                        'menu-item-position' => $position++
                    ]);
                }
            }
            
            // Assign to theme location
            $locations = get_theme_mod('nav_menu_locations');
            $locations['primary'] = $primary_menu_id;
            set_theme_mod('nav_menu_locations', $locations);
        }
        
        // Create Footer Menu
        $footer_menu_id = wp_create_nav_menu('Footer Menu');
        if (!is_wp_error($footer_menu_id)) {
            echo "✅ Footer menu created\n";
            
            // Add menu items
            $footer_items = [
                'Privacy Policy' => isset($this->created_pages['privacy-policy']) ? $this->created_pages['privacy-policy'] : 0,
                'Affiliate Disclosure' => isset($this->created_pages['affiliate-disclosure']) ? $this->created_pages['affiliate-disclosure'] : 0,
                'Contact' => isset($this->created_pages['contact']) ? $this->created_pages['contact'] : 0
            ];
            
            $position = 1;
            foreach ($footer_items as $title => $page_id) {
                if ($page_id) {
                    wp_update_nav_menu_item($footer_menu_id, 0, [
                        'menu-item-title' => $title,
                        'menu-item-object' => 'page',
                        'menu-item-object-id' => $page_id,
                        'menu-item-type' => 'post_type',
                        'menu-item-status' => 'publish',
                        'menu-item-position' => $position++
                    ]);
                }
            }
            
            // Assign to theme location
            $locations = get_theme_mod('nav_menu_locations');
            $locations['footer'] = $footer_menu_id;
            set_theme_mod('nav_menu_locations', $locations);
        }
    }
    
    /**
     * Configure WordPress settings
     */
    private function configure_settings() {
        echo "\n<strong>Configuring settings...</strong>\n";
        
        // Set homepage
        if (isset($this->created_pages['home'])) {
            update_option('show_on_front', 'page');
            update_option('page_on_front', $this->created_pages['home']);
            echo "✅ Homepage set\n";
        }
        
        // Create a blog page
        $blog_page_id = wp_insert_post([
            'post_title' => 'Blog',
            'post_content' => '',
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_name' => 'blog'
        ]);
        
        if ($blog_page_id) {
            update_option('page_for_posts', $blog_page_id);
            echo "✅ Blog page created and set\n";
        }
        
        // Set permalink structure
        update_option('permalink_structure', '/%postname%/');
        echo "✅ Permalinks set to post name\n";
        
        // Set timezone
        update_option('timezone_string', 'America/New_York');
        
        // Set date format
        update_option('date_format', 'F j, Y');
        
        // Set time format
        update_option('time_format', 'g:i a');
    }
    
    /**
     * Setup widgets
     */
    private function setup_widgets() {
        echo "\n<strong>Setting up widgets...</strong>\n";
        
        // This would require theme-specific widget areas
        // For now, we'll skip this as it depends on the theme
        echo "⚠️  Widget setup skipped (theme-dependent)\n";
    }
    
    /**
     * Get content from HTML file
     */
    private function get_content_from_file($filename) {
        $file_path = $this->content_path . 'site-content/' . $filename;
        
        if (file_exists($file_path)) {
            $content = file_get_contents($file_path);
            // Remove any DOCTYPE, html, head, body tags if present
            $content = preg_replace('/<\!DOCTYPE.*?>/is', '', $content);
            $content = preg_replace('/<html.*?>/is', '', $content);
            $content = preg_replace('/<\/html>/is', '', $content);
            $content = preg_replace('/<head>.*?<\/head>/is', '', $content);
            $content = preg_replace('/<body.*?>/is', '', $content);
            $content = preg_replace('/<\/body>/is', '', $content);
            return trim($content);
        }
        
        return false;
    }
    
    /**
     * Get legal content from file
     */
    private function get_legal_content($filename) {
        $file_path = $this->content_path . 'legal-pages/' . $filename;
        
        if (file_exists($file_path)) {
            return file_get_contents($file_path);
        }
        
        return false;
    }
}

// Run the setup
$setup = new FrenchieAllergySetup();
$setup->run_setup();