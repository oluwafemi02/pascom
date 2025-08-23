<?php
/**
 * Frenchie Allergy Help - WordPress Automated Setup Script (Corrected)
 * 
 * This script automatically sets up a WordPress site with predefined content,
 * categories, pages, posts, and menus for a French Bulldog allergy help website.
 */

// Ensure this is run in WordPress context
if (!defined('ABSPATH')) {
    // Try to load WordPress - adjust path as needed
    $wp_load_paths = [
        '../wp-load.php',
        '../../wp-load.php',
        '../../../wp-load.php',
        'wp-load.php'
    ];
    
    $wp_loaded = false;
    foreach ($wp_load_paths as $path) {
        if (file_exists($path)) {
            require_once($path);
            $wp_loaded = true;
            break;
        }
    }
    
    if (!$wp_loaded) {
        die('Error: Could not load WordPress. Please adjust the path to wp-load.php');
    }
}

class FrenchieAllergySetup {
    
    private $created_pages = [];
    private $created_posts = [];
    
    /**
     * Main setup function
     */
    public function run() {
        echo "<h2>🐾 Frenchie Allergy Help - Automated Setup</h2>\n";
        
        // Run setup steps
        $this->create_categories();
        $this->create_pages();
        $this->create_posts();
        $this->setup_menus();
        $this->configure_settings();
        $this->setup_widgets();
        
        echo "\n<strong style='color: green;'>✅ Setup completed successfully!</strong>\n";
        echo "\nNext steps:\n";
        echo "1. Delete this setup file for security\n";
        echo "2. Visit your site to see the changes\n";
        echo "3. Customize content as needed\n";
    }
    
    /**
     * Create categories
     */
    private function create_categories() {
        echo "<strong>Creating categories...</strong>\n";
        
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
            
            if (!is_wp_error($result)) {
                echo "✅ Category '$name' created\n";
            } else {
                echo "⚠️  Category '$name' already exists or error: " . $result->get_error_message() . "\n";
            }
        }
    }
    
    /**
     * Create pages
     */
    private function create_pages() {
        echo "\n<strong>Creating pages...</strong>\n";
        
        $pages = [
            [
                'title' => 'Home',
                'slug' => 'home',
                'template' => 'default',
                'content_file' => 'home.html'
            ],
            [
                'title' => 'About',
                'slug' => 'about',
                'template' => 'default',
                'content_file' => 'about.html'
            ],
            [
                'title' => 'Start Here',
                'slug' => 'start-here',
                'template' => 'default',
                'content_file' => 'start-here.html'
            ],
            [
                'title' => 'Contact',
                'slug' => 'contact',
                'template' => 'default',
                'content_file' => 'contact.html'
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'template' => 'default',
                'content_file' => 'privacy-policy.html',
                'is_legal' => true
            ],
            [
                'title' => 'Affiliate Disclosure',
                'slug' => 'affiliate-disclosure',
                'template' => 'default',
                'content_file' => 'affiliate-disclosure.html',
                'is_legal' => true
            ]
        ];
        
        foreach ($pages as $page_data) {
            $content = $this->get_page_content($page_data['content_file'], isset($page_data['is_legal']) && $page_data['is_legal']);
            
            if ($content !== false) {
                $page_id = wp_insert_post([
                    'post_title' => $page_data['title'],
                    'post_content' => $content,
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'post_name' => $page_data['slug'],
                    'page_template' => $page_data['template']
                ]);
                
                if ($page_id) {
                    $this->created_pages[$page_data['slug']] = $page_id;
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
     * Create posts
     */
    private function create_posts() {
        echo "\n<strong>Creating posts...</strong>\n";
        
        $posts = [
            [
                'title' => 'Understanding French Bulldog Allergies: A Complete Guide',
                'slug' => 'understanding-french-bulldog-allergies',
                'file' => 'french-bulldog-seasonal-allergies-guide.html',
                'categories' => ['Allergies', 'Health']
            ],
            [
                'title' => 'Common Food Allergies in French Bulldogs',
                'slug' => 'common-food-allergies-french-bulldogs',
                'file' => 'french-bulldog-food-allergies-complete-guide.html',
                'categories' => ['Allergies', 'Nutrition']
            ]
        ];
        
        foreach ($posts as $post_data) {
            $content = $this->get_article_content($post_data['file']);
            
            if ($content !== false) {
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
        
        // Create a blog page
        $blog_page_id = wp_insert_post([
            'post_title' => 'Blog',
            'post_content' => '',
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_name' => 'blog'
        ]);
        
        if ($blog_page_id) {
            echo "✅ Blog page created and set\n";
            update_option('page_for_posts', $blog_page_id);
        }
        
        // Set homepage
        if (isset($this->created_pages['home'])) {
            update_option('page_on_front', $this->created_pages['home']);
            update_option('show_on_front', 'page');
        }
        
        // Update general settings
        update_option('blogname', 'Frenchie Allergy Help');
        update_option('blogdescription', 'Your guide to managing French Bulldog allergies');
        
        // Permalink structure
        update_option('permalink_structure', '/%postname%/');
        echo "✅ Permalinks set to post name\n";
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    /**
     * Setup widgets (theme-dependent)
     */
    private function setup_widgets() {
        echo "\n<strong>Setting up widgets...</strong>\n";
        echo "⚠️  Widget setup skipped (theme-dependent)\n";
        // Widget setup would depend on the theme's widget areas
    }
    
    /**
     * Get page content from file
     */
    private function get_page_content($filename, $is_legal = false) {
        // Try multiple possible locations
        $possible_paths = [];
        
        if ($is_legal) {
            $possible_paths[] = dirname(__FILE__) . '/legal-pages/' . $filename;
            $possible_paths[] = dirname(dirname(__FILE__)) . '/legal-pages/' . $filename;
            $possible_paths[] = '/workspace/legal-pages/' . $filename;
        } else {
            $possible_paths[] = dirname(__FILE__) . '/site-content/pages/' . $filename;
            $possible_paths[] = dirname(dirname(__FILE__)) . '/site-content/pages/' . $filename;
            $possible_paths[] = '/workspace/site-content/pages/' . $filename;
        }
        
        foreach ($possible_paths as $file_path) {
            if (file_exists($file_path)) {
                $content = file_get_contents($file_path);
                // Clean up HTML if needed
                $content = $this->clean_html_content($content);
                return $content;
            }
        }
        
        return false;
    }
    
    /**
     * Get article content from file
     */
    private function get_article_content($filename) {
        // Try multiple possible locations
        $possible_paths = [
            dirname(__FILE__) . '/site-content/articles/' . $filename,
            dirname(dirname(__FILE__)) . '/site-content/articles/' . $filename,
            '/workspace/site-content/articles/' . $filename
        ];
        
        foreach ($possible_paths as $file_path) {
            if (file_exists($file_path)) {
                $content = file_get_contents($file_path);
                // Clean up HTML if needed
                $content = $this->clean_html_content($content);
                return $content;
            }
        }
        
        return false;
    }
    
    /**
     * Clean HTML content
     */
    private function clean_html_content($content) {
        // Remove any DOCTYPE, html, head, body tags if present
        $content = preg_replace('/<\!DOCTYPE.*?>/is', '', $content);
        $content = preg_replace('/<html.*?>/is', '', $content);
        $content = preg_replace('/<\/html>/is', '', $content);
        $content = preg_replace('/<head>.*?<\/head>/is', '', $content);
        $content = preg_replace('/<body.*?>/is', '', $content);
        $content = preg_replace('/<\/body>/is', '', $content);
        return trim($content);
    }
}

// Run the setup
$setup = new FrenchieAllergySetup();
$setup->run();