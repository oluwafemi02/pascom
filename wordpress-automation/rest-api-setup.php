<?php
/**
 * WordPress REST API Setup Script
 * 
 * This script uses the WordPress REST API to set up content remotely
 * Can be run from any server that can access your WordPress site
 * 
 * Usage:
 * 1. Update the configuration below with your WordPress details
 * 2. Run: php rest-api-setup.php
 */

// Configuration
$config = [
    'site_url' => 'http://frenchie-allergy-help.local', // Your WordPress URL
    'username' => 'your_username', // WordPress admin username
    'password' => 'your_password', // WordPress admin password or application password
    'content_path' => __DIR__ . '/../site-content/', // Path to content files
    'legal_path' => __DIR__ . '/../legal-pages/' // Path to legal files
];

class WordPressRESTSetup {
    
    private $site_url;
    private $auth_header;
    private $content_path;
    private $legal_path;
    
    public function __construct($config) {
        $this->site_url = rtrim($config['site_url'], '/');
        $this->auth_header = 'Basic ' . base64_encode($config['username'] . ':' . $config['password']);
        $this->content_path = $config['content_path'];
        $this->legal_path = $config['legal_path'];
    }
    
    /**
     * Main setup function
     */
    public function run() {
        echo "🐾 WordPress REST API Setup\n";
        echo "==========================\n\n";
        
        // Test authentication
        if (!$this->test_auth()) {
            echo "❌ Authentication failed. Please check your credentials.\n";
            return;
        }
        
        echo "✅ Authentication successful\n\n";
        
        // Run setup steps
        $this->create_categories();
        $this->create_pages();
        $this->create_posts();
        $this->configure_settings();
        
        echo "\n✅ Setup complete!\n";
    }
    
    /**
     * Test authentication
     */
    private function test_auth() {
        $response = $this->api_request('GET', '/wp/v2/users/me');
        return $response !== false && isset($response['id']);
    }
    
    /**
     * Create categories
     */
    private function create_categories() {
        echo "📁 Creating Categories...\n";
        
        $categories = [
            ['name' => 'Food Allergies', 'slug' => 'food-allergies'],
            ['name' => 'Environmental Allergies', 'slug' => 'environmental-allergies'],
            ['name' => 'Skin Care', 'slug' => 'skin-care'],
            ['name' => 'Product Reviews', 'slug' => 'product-reviews'],
            ['name' => 'Treatment Guides', 'slug' => 'treatment-guides']
        ];
        
        foreach ($categories as $category) {
            // Check if exists
            $existing = $this->api_request('GET', '/wp/v2/categories?slug=' . $category['slug']);
            if ($existing && count($existing) > 0) {
                echo "- Category already exists: {$category['name']}\n";
                continue;
            }
            
            // Create category
            $result = $this->api_request('POST', '/wp/v2/categories', $category);
            if ($result && isset($result['id'])) {
                echo "✓ Created category: {$category['name']}\n";
            } else {
                echo "⚠ Failed to create category: {$category['name']}\n";
            }
        }
    }
    
    /**
     * Create pages
     */
    private function create_pages() {
        echo "\n📄 Creating Pages...\n";
        
        $pages = [
            [
                'title' => 'About',
                'slug' => 'about',
                'file' => $this->content_path . 'pages/about.html'
            ],
            [
                'title' => 'Start Here',
                'slug' => 'start-here',
                'file' => $this->content_path . 'pages/start-here.html'
            ],
            [
                'title' => 'Medical Disclaimer',
                'slug' => 'medical-disclaimer',
                'file' => $this->content_path . 'pages/medical-disclaimer.html'
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'file' => $this->legal_path . 'privacy-policy.html'
            ],
            [
                'title' => 'Affiliate Disclosure',
                'slug' => 'affiliate-disclosure',
                'file' => $this->legal_path . 'affiliate-disclosure.html'
            ],
            [
                'title' => 'Contact',
                'slug' => 'contact',
                'content' => $this->get_contact_content()
            ]
        ];
        
        foreach ($pages as $page) {
            // Check if exists
            $existing = $this->api_request('GET', '/wp/v2/pages?slug=' . $page['slug']);
            if ($existing && count($existing) > 0) {
                echo "- Page already exists: {$page['title']}\n";
                continue;
            }
            
            // Get content
            if (isset($page['content'])) {
                $content = $page['content'];
            } else {
                $content = $this->get_file_content($page['file']);
                if (!$content) {
                    echo "⚠ Could not read file: {$page['file']}\n";
                    continue;
                }
            }
            
            // Create page
            $data = [
                'title' => $page['title'],
                'content' => $content,
                'status' => 'publish',
                'slug' => $page['slug']
            ];
            
            $result = $this->api_request('POST', '/wp/v2/pages', $data);
            if ($result && isset($result['id'])) {
                echo "✓ Created page: {$page['title']}\n";
            } else {
                echo "⚠ Failed to create page: {$page['title']}\n";
            }
        }
    }
    
    /**
     * Create posts
     */
    private function create_posts() {
        echo "\n📝 Creating Posts...\n";
        
        $posts = [
            [
                'title' => 'French Bulldog Food Allergies: Complete Guide',
                'slug' => 'french-bulldog-food-allergies-guide',
                'file' => $this->content_path . 'articles/food-allergies-guide.html',
                'categories' => ['Food Allergies', 'Treatment Guides']
            ],
            [
                'title' => 'French Bulldog Seasonal Allergies: Management Guide',
                'slug' => 'french-bulldog-seasonal-allergies-guide',
                'file' => $this->content_path . 'articles/seasonal-allergies-guide.html',
                'categories' => ['Environmental Allergies', 'Treatment Guides']
            ]
        ];
        
        foreach ($posts as $post) {
            // Check if exists
            $existing = $this->api_request('GET', '/wp/v2/posts?slug=' . $post['slug']);
            if ($existing && count($existing) > 0) {
                echo "- Post already exists: {$post['title']}\n";
                continue;
            }
            
            // Get content
            $content = $this->get_file_content($post['file']);
            if (!$content) {
                echo "⚠ Could not read file: {$post['file']}\n";
                continue;
            }
            
            // Get category IDs
            $category_ids = [];
            foreach ($post['categories'] as $cat_name) {
                $cats = $this->api_request('GET', '/wp/v2/categories?search=' . urlencode($cat_name));
                if ($cats && count($cats) > 0) {
                    $category_ids[] = $cats[0]['id'];
                }
            }
            
            // Create post
            $data = [
                'title' => $post['title'],
                'content' => $content,
                'status' => 'publish',
                'slug' => $post['slug'],
                'categories' => $category_ids
            ];
            
            $result = $this->api_request('POST', '/wp/v2/posts', $data);
            if ($result && isset($result['id'])) {
                echo "✓ Created post: {$post['title']}\n";
            } else {
                echo "⚠ Failed to create post: {$post['title']}\n";
            }
        }
    }
    
    /**
     * Configure settings
     */
    private function configure_settings() {
        echo "\n⚙️ Configuring Settings...\n";
        
        // Note: Some settings require additional plugins or direct database access
        echo "ℹ Please configure the following manually:\n";
        echo "  - Permalink structure (Settings → Permalinks → Post name)\n";
        echo "  - Homepage settings (Settings → Reading)\n";
        echo "  - Menu configuration (Appearance → Menus)\n";
        echo "  - Widget setup (Appearance → Widgets)\n";
    }
    
    /**
     * Make API request
     */
    private function api_request($method, $endpoint, $data = null) {
        $url = $this->site_url . '/wp-json' . $endpoint;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: ' . $this->auth_header,
            'Content-Type: application/json'
        ]);
        
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        }
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_code >= 200 && $http_code < 300) {
            return json_decode($response, true);
        }
        
        return false;
    }
    
    /**
     * Get file content
     */
    private function get_file_content($file_path) {
        if (!file_exists($file_path)) {
            return false;
        }
        
        $content = file_get_contents($file_path);
        
        // Extract body content if full HTML
        if (preg_match('/<body[^>]*>(.*?)<\/body>/is', $content, $matches)) {
            $content = $matches[1];
        }
        
        return $content;
    }
    
    /**
     * Get contact page content
     */
    private function get_contact_content() {
        return '<h2>Get in Touch</h2>
        <p>Have questions about your Frenchie\'s allergies? We\'re here to help!</p>
        <p><em>Contact form will appear here after Contact Form 7 plugin installation.</em></p>
        <h3>Quick Response Times</h3>
        <p>We typically respond within 24-48 hours. For urgent allergy concerns, please consult your veterinarian immediately.</p>';
    }
}

// Run the setup
if (php_sapi_name() === 'cli') {
    $setup = new WordPressRESTSetup($config);
    $setup->run();
} else {
    echo "<pre>";
    $setup = new WordPressRESTSetup($config);
    $setup->run();
    echo "</pre>";
}