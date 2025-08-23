<?php
/**
 * Plugin Name: Frenchie Email Automation
 * Plugin URI: https://frenchieallergyhelp.com
 * Description: Email automation and lead capture for Frenchie Allergy Help
 * Version: 1.0.0
 * Author: Frenchie Care Team
 * License: GPL v2 or later
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Create database tables on activation
register_activation_hook(__FILE__, 'frenchie_email_create_tables');
function frenchie_email_create_tables() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    
    // Subscribers table
    $subscribers_table = $wpdb->prefix . 'frenchie_subscribers';
    $sql1 = "CREATE TABLE $subscribers_table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        email varchar(100) NOT NULL,
        name varchar(100),
        status varchar(20) DEFAULT 'active',
        lead_magnet varchar(50),
        signup_date datetime DEFAULT CURRENT_TIMESTAMP,
        last_email_sent datetime,
        tags text,
        PRIMARY KEY (id),
        UNIQUE KEY email (email)
    ) $charset_collate;";
    
    // Email sequences table
    $sequences_table = $wpdb->prefix . 'frenchie_email_sequences';
    $sql2 = "CREATE TABLE $sequences_table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        sequence_name varchar(100) NOT NULL,
        email_subject varchar(200) NOT NULL,
        email_content longtext NOT NULL,
        days_delay int DEFAULT 0,
        sequence_order int DEFAULT 0,
        active tinyint(1) DEFAULT 1,
        PRIMARY KEY (id)
    ) $charset_collate;";
    
    // Email log table
    $email_log_table = $wpdb->prefix . 'frenchie_email_log';
    $sql3 = "CREATE TABLE $email_log_table (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        subscriber_id mediumint(9) NOT NULL,
        sequence_id mediumint(9),
        email_type varchar(50),
        sent_date datetime DEFAULT CURRENT_TIMESTAMP,
        opened tinyint(1) DEFAULT 0,
        clicked tinyint(1) DEFAULT 0,
        PRIMARY KEY (id)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql1);
    dbDelta($sql2);
    dbDelta($sql3);
    
    // Insert default email sequences
    frenchie_email_insert_default_sequences();
}

// Insert default email sequences
function frenchie_email_insert_default_sequences() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'frenchie_email_sequences';
    
    $default_sequences = array(
        array(
            'sequence_name' => 'welcome_series',
            'email_subject' => 'Welcome! Your Free Frenchie Allergy Guide is Here',
            'email_content' => '<h2>Welcome to the Frenchie Allergy Help Family!</h2>
<p>Hi {name},</p>
<p>Thank you for joining our community of caring Frenchie parents. As promised, here\'s your free Frenchie Allergy Quick-Start Guide:</p>
<p><a href="{download_link}" style="background: #48BB78; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">Download Your Free Guide</a></p>
<p>This guide covers:</p>
<ul>
<li>5 most common allergy triggers in French Bulldogs</li>
<li>Emergency symptoms checklist</li>
<li>Daily prevention routine</li>
<li>When to see your vet</li>
</ul>
<p>Tomorrow, I\'ll send you our most popular article on identifying yeast infections vs allergies - it\'s helped thousands of Frenchie parents!</p>
<p>Best,<br>The Frenchie Care Team</p>',
            'days_delay' => 0,
            'sequence_order' => 1
        ),
        array(
            'sequence_name' => 'welcome_series',
            'email_subject' => 'Is it a Yeast Infection or Allergy? (Photo Guide)',
            'email_content' => '<h2>The #1 Question Every Frenchie Parent Asks</h2>
<p>Hi {name},</p>
<p>Yesterday you downloaded our allergy guide. Today, I want to share our most popular article that\'s helped over 5,000 Frenchie parents.</p>
<p><a href="https://frenchieallergyhelp.com/french-bulldog-yeast-vs-allergy">Read: Yeast Infection vs Allergy - Complete Photo Guide</a></p>
<p>This guide shows you:</p>
<ul>
<li>Visual differences between yeast and allergies</li>
<li>The "sniff test" that vets use</li>
<li>Which one responds to treatment faster</li>
<li>When both conditions occur together</li>
</ul>
<p>Quick tip: If your Frenchie has a musty, bread-like smell, it\'s likely yeast!</p>
<p>Tomorrow: The 3 foods that trigger 80% of Frenchie allergies</p>
<p>Best,<br>The Frenchie Care Team</p>',
            'days_delay' => 1,
            'sequence_order' => 2
        ),
        array(
            'sequence_name' => 'welcome_series',
            'email_subject' => '3 Foods Causing 80% of Frenchie Allergies',
            'email_content' => '<h2>The Hidden Culprits in Your Frenchie\'s Bowl</h2>
<p>Hi {name},</p>
<p>After analyzing data from thousands of Frenchie parents, we\'ve identified the top 3 food allergens:</p>
<ol>
<li><strong>Chicken</strong> - Found in 70% of dog foods (even "beef" flavored ones!)</li>
<li><strong>Grains</strong> - Wheat, corn, and soy are major triggers</li>
<li><strong>Dairy</strong> - Yes, even those "harmless" cheese treats</li>
</ol>
<p>What to do next:</p>
<p>Try an elimination diet starting with a novel protein like duck or venison. Our Frenchie Allergy Toolkit includes a complete 7-week elimination diet planner.</p>
<p><a href="https://frenchieallergyhelp.com/frenchie-allergy-toolkit" style="background: #ED8936; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">Get the Toolkit (€9 Today)</a></p>
<p>P.S. The toolkit has helped 5,000+ Frenchie parents identify their dog\'s triggers without expensive allergy tests.</p>
<p>Best,<br>The Frenchie Care Team</p>',
            'days_delay' => 2,
            'sequence_order' => 3
        )
    );
    
    foreach ($default_sequences as $sequence) {
        $wpdb->insert($table_name, $sequence);
    }
}

// Handle form submissions
add_action('init', 'frenchie_email_handle_subscription');
function frenchie_email_handle_subscription() {
    if (isset($_POST['frenchie_subscribe']) && isset($_POST['email'])) {
        $email = sanitize_email($_POST['email']);
        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $lead_magnet = isset($_POST['lead_magnet']) ? sanitize_text_field($_POST['lead_magnet']) : 'general';
        
        if (is_email($email)) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'frenchie_subscribers';
            
            $existing = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table_name WHERE email = %s", $email));
            
            if (!$existing) {
                $wpdb->insert($table_name, array(
                    'email' => $email,
                    'name' => $name,
                    'lead_magnet' => $lead_magnet,
                    'tags' => $lead_magnet
                ));
                
                // Send welcome email
                frenchie_email_send_welcome($email, $name);
                
                // Redirect to thank you page
                wp_redirect(home_url('/thank-you'));
                exit;
            }
        }
    }
}

// Send welcome email
function frenchie_email_send_welcome($email, $name) {
    global $wpdb;
    $sequences_table = $wpdb->prefix . 'frenchie_email_sequences';
    
    $welcome_email = $wpdb->get_row("SELECT * FROM $sequences_table WHERE sequence_name = 'welcome_series' AND sequence_order = 1");
    
    if ($welcome_email) {
        $subject = $welcome_email->email_subject;
        $content = str_replace(
            array('{name}', '{download_link}'),
            array($name ?: 'Friend', home_url('/download/allergy-guide')),
            $welcome_email->email_content
        );
        
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: Frenchie Care Team <hello@frenchieallergyhelp.com>'
        );
        
        wp_mail($email, $subject, $content, $headers);
        
        // Log email
        $subscriber_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM " . $wpdb->prefix . "frenchie_subscribers WHERE email = %s", $email));
        $wpdb->insert($wpdb->prefix . 'frenchie_email_log', array(
            'subscriber_id' => $subscriber_id,
            'sequence_id' => $welcome_email->id,
            'email_type' => 'welcome'
        ));
    }
}

// Schedule daily email sends
add_action('wp', 'frenchie_email_schedule_sends');
function frenchie_email_schedule_sends() {
    if (!wp_next_scheduled('frenchie_daily_email_send')) {
        wp_schedule_event(time(), 'daily', 'frenchie_daily_email_send');
    }
}

// Process daily email sends
add_action('frenchie_daily_email_send', 'frenchie_process_email_sequences');
function frenchie_process_email_sequences() {
    global $wpdb;
    $subscribers_table = $wpdb->prefix . 'frenchie_subscribers';
    $sequences_table = $wpdb->prefix . 'frenchie_email_sequences';
    $log_table = $wpdb->prefix . 'frenchie_email_log';
    
    // Get active subscribers
    $subscribers = $wpdb->get_results("SELECT * FROM $subscribers_table WHERE status = 'active'");
    
    foreach ($subscribers as $subscriber) {
        // Calculate days since signup
        $signup_date = new DateTime($subscriber->signup_date);
        $today = new DateTime();
        $days_since_signup = $today->diff($signup_date)->days;
        
        // Get next email in sequence
        $next_email = $wpdb->get_row($wpdb->prepare("
            SELECT s.* FROM $sequences_table s
            WHERE s.sequence_name = 'welcome_series'
            AND s.days_delay <= %d
            AND s.active = 1
            AND s.id NOT IN (
                SELECT sequence_id FROM $log_table 
                WHERE subscriber_id = %d AND sequence_id IS NOT NULL
            )
            ORDER BY s.sequence_order ASC
            LIMIT 1
        ", $days_since_signup, $subscriber->id));
        
        if ($next_email && $next_email->days_delay == $days_since_signup) {
            // Send email
            $subject = $next_email->email_subject;
            $content = str_replace(
                array('{name}', '{email}'),
                array($subscriber->name ?: 'Friend', $subscriber->email),
                $next_email->email_content
            );
            
            $headers = array(
                'Content-Type: text/html; charset=UTF-8',
                'From: Frenchie Care Team <hello@frenchieallergyhelp.com>'
            );
            
            wp_mail($subscriber->email, $subject, $content, $headers);
            
            // Log email
            $wpdb->insert($log_table, array(
                'subscriber_id' => $subscriber->id,
                'sequence_id' => $next_email->id,
                'email_type' => 'sequence'
            ));
            
            // Update last email sent
            $wpdb->update($subscribers_table, 
                array('last_email_sent' => current_time('mysql')), 
                array('id' => $subscriber->id)
            );
        }
    }
}

// Admin menu
add_action('admin_menu', 'frenchie_email_admin_menu');
function frenchie_email_admin_menu() {
    add_menu_page(
        'Email Automation',
        'Email Automation',
        'manage_options',
        'frenchie-email',
        'frenchie_email_dashboard',
        'dashicons-email',
        35
    );
    
    add_submenu_page(
        'frenchie-email',
        'Subscribers',
        'Subscribers',
        'manage_options',
        'frenchie-subscribers',
        'frenchie_email_subscribers_page'
    );
    
    add_submenu_page(
        'frenchie-email',
        'Email Sequences',
        'Email Sequences',
        'manage_options',
        'frenchie-sequences',
        'frenchie_email_sequences_page'
    );
}

// Dashboard page
function frenchie_email_dashboard() {
    global $wpdb;
    $subscribers_table = $wpdb->prefix . 'frenchie_subscribers';
    $log_table = $wpdb->prefix . 'frenchie_email_log';
    
    $total_subscribers = $wpdb->get_var("SELECT COUNT(*) FROM $subscribers_table WHERE status = 'active'");
    $this_month = $wpdb->get_var("SELECT COUNT(*) FROM $subscribers_table WHERE status = 'active' AND MONTH(signup_date) = MONTH(CURRENT_DATE())");
    $total_sent = $wpdb->get_var("SELECT COUNT(*) FROM $log_table");
    
    ?>
    <div class="wrap">
        <h1>Email Automation Dashboard</h1>
        
        <div class="email-stats" style="display: flex; gap: 20px; margin: 20px 0;">
            <div class="stat-box" style="background: white; padding: 20px; border: 1px solid #ddd; flex: 1;">
                <h3>Active Subscribers</h3>
                <p style="font-size: 36px; margin: 0; color: #2C5282;"><?php echo number_format($total_subscribers); ?></p>
            </div>
            <div class="stat-box" style="background: white; padding: 20px; border: 1px solid #ddd; flex: 1;">
                <h3>New This Month</h3>
                <p style="font-size: 36px; margin: 0; color: #48BB78;"><?php echo number_format($this_month); ?></p>
            </div>
            <div class="stat-box" style="background: white; padding: 20px; border: 1px solid #ddd; flex: 1;">
                <h3>Emails Sent</h3>
                <p style="font-size: 36px; margin: 0; color: #ED8936;"><?php echo number_format($total_sent); ?></p>
            </div>
        </div>
        
        <h2>Quick Actions</h2>
        <p>
            <a href="<?php echo admin_url('admin.php?page=frenchie-subscribers'); ?>" class="button button-primary">View Subscribers</a>
            <a href="<?php echo admin_url('admin.php?page=frenchie-sequences'); ?>" class="button">Manage Email Sequences</a>
        </p>
        
        <h2>Lead Capture Forms</h2>
        <p>Use these shortcodes to add lead capture forms to your site:</p>
        <ul>
            <li><code>[frenchie_subscribe]</code> - Basic email capture form</li>
            <li><code>[frenchie_subscribe lead_magnet="allergy_guide"]</code> - With specific lead magnet</li>
            <li><code>[frenchie_subscribe show_name="true"]</code> - Include name field</li>
        </ul>
    </div>
    <?php
}

// Subscribers page
function frenchie_email_subscribers_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'frenchie_subscribers';
    
    $subscribers = $wpdb->get_results("SELECT * FROM $table_name ORDER BY signup_date DESC LIMIT 100");
    
    ?>
    <div class="wrap">
        <h1>Email Subscribers</h1>
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Lead Magnet</th>
                    <th>Signup Date</th>
                    <th>Last Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subscribers as $subscriber) : ?>
                <tr>
                    <td><?php echo esc_html($subscriber->email); ?></td>
                    <td><?php echo esc_html($subscriber->name); ?></td>
                    <td><?php echo esc_html($subscriber->status); ?></td>
                    <td><?php echo esc_html($subscriber->lead_magnet); ?></td>
                    <td><?php echo esc_html($subscriber->signup_date); ?></td>
                    <td><?php echo esc_html($subscriber->last_email_sent); ?></td>
                    <td>
                        <a href="#">View</a> | 
                        <a href="#">Unsubscribe</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

// Email sequences page
function frenchie_email_sequences_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'frenchie_email_sequences';
    
    $sequences = $wpdb->get_results("SELECT * FROM $table_name ORDER BY sequence_name, sequence_order");
    
    ?>
    <div class="wrap">
        <h1>Email Sequences</h1>
        
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Sequence</th>
                    <th>Subject</th>
                    <th>Delay (days)</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sequences as $sequence) : ?>
                <tr>
                    <td><?php echo esc_html($sequence->sequence_name); ?></td>
                    <td><?php echo esc_html($sequence->email_subject); ?></td>
                    <td><?php echo esc_html($sequence->days_delay); ?></td>
                    <td><?php echo esc_html($sequence->sequence_order); ?></td>
                    <td><?php echo $sequence->active ? 'Active' : 'Inactive'; ?></td>
                    <td>
                        <a href="#">Edit</a> | 
                        <a href="#">Preview</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

// Subscribe form shortcode
add_shortcode('frenchie_subscribe', 'frenchie_subscribe_form_shortcode');
function frenchie_subscribe_form_shortcode($atts) {
    $atts = shortcode_atts(array(
        'lead_magnet' => 'general',
        'show_name' => 'false',
        'button_text' => 'Get Free Guide',
        'title' => 'Get Your Free Frenchie Allergy Guide'
    ), $atts);
    
    ob_start();
    ?>
    <div class="frenchie-subscribe-form">
        <h3><?php echo esc_html($atts['title']); ?></h3>
        <form method="post" action="">
            <?php if ($atts['show_name'] === 'true') : ?>
                <p>
                    <input type="text" name="name" placeholder="Your name" style="width: 100%; padding: 10px;">
                </p>
            <?php endif; ?>
            <p>
                <input type="email" name="email" placeholder="Your email address" required style="width: 100%; padding: 10px;">
            </p>
            <input type="hidden" name="lead_magnet" value="<?php echo esc_attr($atts['lead_magnet']); ?>">
            <input type="hidden" name="frenchie_subscribe" value="1">
            <p>
                <button type="submit" style="background: #48BB78; color: white; padding: 15px 30px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; width: 100%;">
                    <?php echo esc_html($atts['button_text']); ?>
                </button>
            </p>
            <p style="font-size: 12px; opacity: 0.7;">We respect your privacy. Unsubscribe anytime.</p>
        </form>
    </div>
    <?php
    return ob_get_clean();
}