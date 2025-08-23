<?php
/**
 * Plugin Name: Frenchie Monetization
 * Plugin URI: https://frenchieallergyhelp.com
 * Description: Monetization features for Frenchie Allergy Help site
 * Version: 1.0.0
 * Author: Frenchie Care Team
 * License: GPL v2 or later
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Plugin activation hook
register_activation_hook(__FILE__, 'frenchie_monetization_activate');
function frenchie_monetization_activate() {
    // Create database table for affiliate links
    global $wpdb;
    $table_name = $wpdb->prefix . 'frenchie_affiliate_links';
    
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        link_name varchar(255) NOT NULL,
        original_url text NOT NULL,
        affiliate_url text NOT NULL,
        merchant varchar(100) NOT NULL,
        clicks int DEFAULT 0,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Add admin menu
add_action('admin_menu', 'frenchie_monetization_menu');
function frenchie_monetization_menu() {
    add_menu_page(
        'Monetization',
        'Monetization',
        'manage_options',
        'frenchie-monetization',
        'frenchie_monetization_dashboard',
        'dashicons-chart-area',
        30
    );
    
    add_submenu_page(
        'frenchie-monetization',
        'Affiliate Links',
        'Affiliate Links',
        'manage_options',
        'frenchie-affiliate-links',
        'frenchie_affiliate_links_page'
    );
    
    add_submenu_page(
        'frenchie-monetization',
        'Ad Settings',
        'Ad Settings',
        'manage_options',
        'frenchie-ad-settings',
        'frenchie_ad_settings_page'
    );
}

// Dashboard page
function frenchie_monetization_dashboard() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'frenchie_affiliate_links';
    
    // Get stats
    $total_clicks = $wpdb->get_var("SELECT SUM(clicks) FROM $table_name");
    $total_links = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    $top_links = $wpdb->get_results("SELECT link_name, clicks, merchant FROM $table_name ORDER BY clicks DESC LIMIT 5");
    
    ?>
    <div class="wrap">
        <h1>Monetization Dashboard</h1>
        
        <div class="monetization-stats">
            <div class="stat-box">
                <h3>Total Affiliate Clicks</h3>
                <p class="stat-number"><?php echo number_format($total_clicks); ?></p>
            </div>
            
            <div class="stat-box">
                <h3>Active Affiliate Links</h3>
                <p class="stat-number"><?php echo number_format($total_links); ?></p>
            </div>
        </div>
        
        <div class="top-performing">
            <h2>Top Performing Links</h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Link Name</th>
                        <th>Merchant</th>
                        <th>Clicks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($top_links as $link) : ?>
                    <tr>
                        <td><?php echo esc_html($link->link_name); ?></td>
                        <td><?php echo esc_html($link->merchant); ?></td>
                        <td><?php echo number_format($link->clicks); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <style>
        .monetization-stats {
            display: flex;
            gap: 20px;
            margin: 20px 0;
        }
        .stat-box {
            background: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            flex: 1;
        }
        .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #2C5282;
            margin: 10px 0;
        }
        .top-performing {
            margin-top: 30px;
        }
        </style>
    </div>
    <?php
}

// Affiliate links page
function frenchie_affiliate_links_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'frenchie_affiliate_links';
    
    // Handle form submission
    if (isset($_POST['add_link'])) {
        $wpdb->insert($table_name, array(
            'link_name' => sanitize_text_field($_POST['link_name']),
            'original_url' => esc_url_raw($_POST['original_url']),
            'affiliate_url' => esc_url_raw($_POST['affiliate_url']),
            'merchant' => sanitize_text_field($_POST['merchant'])
        ));
        echo '<div class="notice notice-success"><p>Link added successfully!</p></div>';
    }
    
    // Get all links
    $links = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");
    
    ?>
    <div class="wrap">
        <h1>Affiliate Links</h1>
        
        <form method="post" class="add-link-form">
            <h2>Add New Link</h2>
            <table class="form-table">
                <tr>
                    <th><label for="link_name">Link Name</label></th>
                    <td><input type="text" name="link_name" id="link_name" class="regular-text" required></td>
                </tr>
                <tr>
                    <th><label for="merchant">Merchant</label></th>
                    <td>
                        <select name="merchant" id="merchant" required>
                            <option value="Zooplus">Zooplus</option>
                            <option value="Amazon">Amazon</option>
                            <option value="Fressnapf">Fressnapf</option>
                            <option value="Other">Other</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="original_url">Original URL</label></th>
                    <td><input type="url" name="original_url" id="original_url" class="large-text" required></td>
                </tr>
                <tr>
                    <th><label for="affiliate_url">Affiliate URL</label></th>
                    <td><input type="url" name="affiliate_url" id="affiliate_url" class="large-text" required></td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="add_link" class="button button-primary" value="Add Link">
            </p>
        </form>
        
        <h2>Existing Links</h2>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Merchant</th>
                    <th>Clicks</th>
                    <th>Shortcode</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($links as $link) : ?>
                <tr>
                    <td><?php echo esc_html($link->link_name); ?></td>
                    <td><?php echo esc_html($link->merchant); ?></td>
                    <td><?php echo number_format($link->clicks); ?></td>
                    <td><code>[affiliate_link id="<?php echo $link->id; ?>"]</code></td>
                    <td>
                        <a href="<?php echo esc_url($link->original_url); ?>" target="_blank">View</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

// Ad settings page
function frenchie_ad_settings_page() {
    // Save settings
    if (isset($_POST['save_settings'])) {
        update_option('frenchie_ad_network', sanitize_text_field($_POST['ad_network']));
        update_option('frenchie_ad_code_header', $_POST['ad_code_header']);
        update_option('frenchie_ad_code_content', $_POST['ad_code_content']);
        update_option('frenchie_ad_code_sidebar', $_POST['ad_code_sidebar']);
        echo '<div class="notice notice-success"><p>Settings saved!</p></div>';
    }
    
    $ad_network = get_option('frenchie_ad_network', 'ezoic');
    $ad_code_header = get_option('frenchie_ad_code_header', '');
    $ad_code_content = get_option('frenchie_ad_code_content', '');
    $ad_code_sidebar = get_option('frenchie_ad_code_sidebar', '');
    
    ?>
    <div class="wrap">
        <h1>Ad Settings</h1>
        
        <form method="post">
            <table class="form-table">
                <tr>
                    <th><label for="ad_network">Ad Network</label></th>
                    <td>
                        <select name="ad_network" id="ad_network">
                            <option value="ezoic" <?php selected($ad_network, 'ezoic'); ?>>Ezoic</option>
                            <option value="mediavine" <?php selected($ad_network, 'mediavine'); ?>>Mediavine</option>
                            <option value="custom" <?php selected($ad_network, 'custom'); ?>>Custom</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="ad_code_header">Header Ad Code</label></th>
                    <td><textarea name="ad_code_header" id="ad_code_header" rows="5" class="large-text"><?php echo esc_textarea($ad_code_header); ?></textarea></td>
                </tr>
                <tr>
                    <th><label for="ad_code_content">In-Content Ad Code</label></th>
                    <td><textarea name="ad_code_content" id="ad_code_content" rows="5" class="large-text"><?php echo esc_textarea($ad_code_content); ?></textarea></td>
                </tr>
                <tr>
                    <th><label for="ad_code_sidebar">Sidebar Ad Code</label></th>
                    <td><textarea name="ad_code_sidebar" id="ad_code_sidebar" rows="5" class="large-text"><?php echo esc_textarea($ad_code_sidebar); ?></textarea></td>
                </tr>
            </table>
            
            <p class="submit">
                <input type="submit" name="save_settings" class="button button-primary" value="Save Settings">
            </p>
        </form>
        
        <div class="ad-network-info">
            <h2>Ad Network Setup Instructions</h2>
            <div class="ezoic-info" style="display: <?php echo $ad_network == 'ezoic' ? 'block' : 'none'; ?>;">
                <h3>Ezoic Setup</h3>
                <ol>
                    <li>Sign up at <a href="https://www.ezoic.com" target="_blank">Ezoic.com</a></li>
                    <li>Add your site and verify ownership</li>
                    <li>Use Ezoic's Cloud Integration or Name Server Integration</li>
                    <li>Place ad placeholders using their Chrome extension</li>
                    <li>No minimum traffic requirement!</li>
                </ol>
            </div>
            <div class="mediavine-info" style="display: <?php echo $ad_network == 'mediavine' ? 'block' : 'none'; ?>;">
                <h3>Mediavine Setup</h3>
                <ol>
                    <li>Apply when you reach 50,000 sessions/month</li>
                    <li>Sign up at <a href="https://www.mediavine.com" target="_blank">Mediavine.com</a></li>
                    <li>Add their script wrapper to your site</li>
                    <li>They handle all ad placements automatically</li>
                    <li>Higher RPMs than Ezoic typically</li>
                </ol>
            </div>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            $('#ad_network').change(function() {
                $('.ad-network-info > div').hide();
                $('.' + $(this).val() + '-info').show();
            });
        });
        </script>
    </div>
    <?php
}

// Affiliate link shortcode
add_shortcode('affiliate_link', 'frenchie_affiliate_link_shortcode');
function frenchie_affiliate_link_shortcode($atts) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'frenchie_affiliate_links';
    
    $atts = shortcode_atts(array(
        'id' => 0,
        'text' => '',
        'class' => 'affiliate-link'
    ), $atts);
    
    $link = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $atts['id']));
    
    if (!$link) {
        return '';
    }
    
    // Update click count via AJAX
    $onclick = "jQuery.post('" . admin_url('admin-ajax.php') . "', {action: 'track_affiliate_click', link_id: " . $link->id . "});";
    
    $text = $atts['text'] ?: $link->link_name;
    
    return sprintf(
        '<a href="%s" class="%s" target="_blank" rel="nofollow noopener" onclick="%s">%s</a>',
        esc_url($link->affiliate_url),
        esc_attr($atts['class']),
        $onclick,
        esc_html($text)
    );
}

// AJAX handler for tracking clicks
add_action('wp_ajax_track_affiliate_click', 'frenchie_track_affiliate_click');
add_action('wp_ajax_nopriv_track_affiliate_click', 'frenchie_track_affiliate_click');
function frenchie_track_affiliate_click() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'frenchie_affiliate_links';
    
    $link_id = intval($_POST['link_id']);
    
    $wpdb->query($wpdb->prepare("UPDATE $table_name SET clicks = clicks + 1 WHERE id = %d", $link_id));
    
    wp_die();
}

// Insert ads in content
add_filter('the_content', 'frenchie_insert_content_ads');
function frenchie_insert_content_ads($content) {
    if (!is_single() || !in_the_loop() || !is_main_query()) {
        return $content;
    }
    
    $ad_code = get_option('frenchie_ad_code_content', '');
    
    if (empty($ad_code)) {
        return $content;
    }
    
    // Insert ad after 3rd paragraph
    $paragraphs = explode('</p>', $content);
    if (count($paragraphs) > 3) {
        $ad_wrapped = '<div class="content-ad">' . $ad_code . '</div>';
        array_splice($paragraphs, 3, 0, $ad_wrapped);
        $content = implode('</p>', $paragraphs);
    }
    
    return $content;
}

// Add header ad
add_action('wp_head', 'frenchie_header_ad_code');
function frenchie_header_ad_code() {
    $ad_code = get_option('frenchie_ad_code_header', '');
    if (!empty($ad_code)) {
        echo $ad_code;
    }
}

// Sidebar ad widget
class Frenchie_Ad_Widget extends WP_Widget {
    function __construct() {
        parent::__construct(
            'frenchie_ad_widget',
            'Frenchie Ad Widget',
            array('description' => 'Display ads in sidebar')
        );
    }
    
    public function widget($args, $instance) {
        $ad_code = get_option('frenchie_ad_code_sidebar', '');
        
        if (!empty($ad_code)) {
            echo $args['before_widget'];
            echo '<div class="sidebar-ad">';
            echo $ad_code;
            echo '</div>';
            echo $args['after_widget'];
        }
    }
}

add_action('widgets_init', function() {
    register_widget('Frenchie_Ad_Widget');
});