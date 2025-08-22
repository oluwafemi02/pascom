<?php
/**
 * Frenchie Care Theme Functions
 *
 * @package FrenchieCare
 */

// Theme Setup
function frenchie_care_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_theme_support('custom-logo');
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'frenchie-care'),
        'footer' => __('Footer Menu', 'frenchie-care'),
    ));
    
    // Content width
    if (!isset($content_width)) {
        $content_width = 1200;
    }
}
add_action('after_setup_theme', 'frenchie_care_setup');

// Enqueue styles and scripts
function frenchie_care_scripts() {
    wp_enqueue_style('frenchie-care-style', get_stylesheet_uri(), array(), '1.0.0');
    wp_enqueue_script('frenchie-care-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'frenchie_care_scripts');

// Register widget areas
function frenchie_care_widgets_init() {
    register_sidebar(array(
        'name' => __('Sidebar', 'frenchie-care'),
        'id' => 'sidebar-1',
        'description' => __('Add widgets here.', 'frenchie-care'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
    
    register_sidebar(array(
        'name' => __('Footer Widget Area', 'frenchie-care'),
        'id' => 'footer-widgets',
        'description' => __('Footer widget area', 'frenchie-care'),
        'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="footer-widget-title">',
        'after_title' => '</h3>',
    ));
}
add_action('widgets_init', 'frenchie_care_widgets_init');

// Custom excerpt length
function frenchie_care_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'frenchie_care_excerpt_length');

// Schema.org structured data for FAQ
function frenchie_care_add_faq_schema($content) {
    if (is_singular('post')) {
        $faqs = get_post_meta(get_the_ID(), 'frenchie_faqs', true);
        if ($faqs) {
            $schema = array(
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => array()
            );
            
            foreach ($faqs as $faq) {
                $schema['mainEntity'][] = array(
                    '@type' => 'Question',
                    'name' => $faq['question'],
                    'acceptedAnswer' => array(
                        '@type' => 'Answer',
                        'text' => $faq['answer']
                    )
                );
            }
            
            $content .= '<script type="application/ld+json">' . json_encode($schema) . '</script>';
        }
    }
    return $content;
}
add_filter('the_content', 'frenchie_care_add_faq_schema');

// Add medical disclaimer to all posts
function frenchie_care_add_disclaimer($content) {
    if (is_singular('post')) {
        $disclaimer = '<div class="medical-disclaimer"><strong>Medical Disclaimer:</strong> This content is for informational purposes only and is not a substitute for professional veterinary advice, diagnosis, or treatment. Always consult with a qualified veterinarian regarding your French Bulldog\'s health concerns.</div>';
        $content = $disclaimer . $content;
    }
    return $content;
}
add_filter('the_content', 'frenchie_care_add_disclaimer', 20);

// Add affiliate disclosure
function frenchie_care_add_affiliate_disclosure($content) {
    if (is_singular('post') && has_tag('affiliate-content')) {
        $disclosure = '<div class="affiliate-disclosure"><strong>Affiliate Disclosure:</strong> This post contains affiliate links. If you make a purchase through these links, we may earn a commission at no extra cost to you. We only recommend products we believe will benefit your French Bulldog.</div>';
        $content = $disclosure . $content;
    }
    return $content;
}
add_filter('the_content', 'frenchie_care_add_affiliate_disclosure', 15);

// Custom shortcode for product boxes
function frenchie_care_product_box($atts) {
    $atts = shortcode_atts(array(
        'title' => '',
        'description' => '',
        'price' => '',
        'link' => '',
        'button_text' => 'Check Price',
    ), $atts);
    
    $output = '<div class="product-box">';
    $output .= '<h3>' . esc_html($atts['title']) . '</h3>';
    $output .= '<p>' . esc_html($atts['description']) . '</p>';
    if ($atts['price']) {
        $output .= '<p class="price"><strong>Price:</strong> ' . esc_html($atts['price']) . '</p>';
    }
    $output .= '<a href="' . esc_url($atts['link']) . '" class="button" target="_blank" rel="nofollow noopener">' . esc_html($atts['button_text']) . '</a>';
    $output .= '</div>';
    
    return $output;
}
add_shortcode('product_box', 'frenchie_care_product_box');

// Custom shortcode for comparison tables
function frenchie_care_comparison_table($atts, $content = null) {
    return '<div class="table-responsive"><table class="comparison-table">' . do_shortcode($content) . '</table></div>';
}
add_shortcode('comparison_table', 'frenchie_care_comparison_table');

// Lead capture shortcode
function frenchie_care_lead_capture($atts) {
    $atts = shortcode_atts(array(
        'title' => 'Get Your Free Frenchie Allergy Guide',
        'description' => 'Join thousands of Frenchie parents getting weekly tips!',
        'button_text' => 'Get Free Guide',
    ), $atts);
    
    $output = '<div class="lead-capture">';
    $output .= '<h3>' . esc_html($atts['title']) . '</h3>';
    $output .= '<p>' . esc_html($atts['description']) . '</p>';
    $output .= '<form action="/subscribe" method="post" class="lead-capture-form">';
    $output .= '<input type="email" name="email" placeholder="Your email address" required>';
    $output .= '<button type="submit">' . esc_html($atts['button_text']) . '</button>';
    $output .= '</form>';
    $output .= '</div>';
    
    return $output;
}
add_shortcode('lead_capture', 'frenchie_care_lead_capture');

// SEO optimizations
function frenchie_care_seo_meta_tags() {
    if (is_singular()) {
        global $post;
        $description = get_post_meta($post->ID, 'meta_description', true);
        if (!$description) {
            $description = wp_trim_words($post->post_excerpt ?: $post->post_content, 30);
        }
        echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
        
        // Open Graph tags
        echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '">' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
        echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">' . "\n";
        echo '<meta property="og:type" content="article">' . "\n";
        
        if (has_post_thumbnail()) {
            $thumbnail_url = get_the_post_thumbnail_url($post->ID, 'large');
            echo '<meta property="og:image" content="' . esc_url($thumbnail_url) . '">' . "\n";
        }
    }
}
add_action('wp_head', 'frenchie_care_seo_meta_tags');

// Optimize images for performance
function frenchie_care_optimize_images($content) {
    // Add loading="lazy" to images
    $content = preg_replace('/<img(.*?)>/i', '<img$1 loading="lazy">', $content);
    return $content;
}
add_filter('the_content', 'frenchie_care_optimize_images');

// Add custom post meta boxes
function frenchie_care_add_meta_boxes() {
    add_meta_box(
        'frenchie_seo_meta',
        'SEO Settings',
        'frenchie_care_seo_meta_box',
        'post',
        'normal',
        'high'
    );
    
    add_meta_box(
        'frenchie_faq_meta',
        'FAQ Schema',
        'frenchie_care_faq_meta_box',
        'post',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'frenchie_care_add_meta_boxes');

// SEO meta box callback
function frenchie_care_seo_meta_box($post) {
    $meta_description = get_post_meta($post->ID, 'meta_description', true);
    wp_nonce_field('frenchie_care_save_meta', 'frenchie_care_meta_nonce');
    ?>
    <p>
        <label for="meta_description">Meta Description:</label><br>
        <textarea id="meta_description" name="meta_description" rows="3" style="width:100%;"><?php echo esc_textarea($meta_description); ?></textarea>
    </p>
    <?php
}

// FAQ meta box callback
function frenchie_care_faq_meta_box($post) {
    $faqs = get_post_meta($post->ID, 'frenchie_faqs', true) ?: array();
    ?>
    <div id="frenchie-faq-container">
        <p>Add FAQ items for schema markup:</p>
        <div id="faq-items">
            <?php foreach ($faqs as $index => $faq): ?>
            <div class="faq-item">
                <p>
                    <label>Question:</label><br>
                    <input type="text" name="frenchie_faqs[<?php echo $index; ?>][question]" value="<?php echo esc_attr($faq['question']); ?>" style="width:100%;">
                </p>
                <p>
                    <label>Answer:</label><br>
                    <textarea name="frenchie_faqs[<?php echo $index; ?>][answer]" rows="3" style="width:100%;"><?php echo esc_textarea($faq['answer']); ?></textarea>
                </p>
                <hr>
            </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button" onclick="addFAQItem()">Add FAQ</button>
    </div>
    <script>
    function addFAQItem() {
        var container = document.getElementById('faq-items');
        var index = container.children.length;
        var html = '<div class="faq-item"><p><label>Question:</label><br><input type="text" name="frenchie_faqs[' + index + '][question]" style="width:100%;"></p><p><label>Answer:</label><br><textarea name="frenchie_faqs[' + index + '][answer]" rows="3" style="width:100%;"></textarea></p><hr></div>';
        container.insertAdjacentHTML('beforeend', html);
    }
    </script>
    <?php
}

// Save meta box data
function frenchie_care_save_meta_boxes($post_id) {
    if (!isset($_POST['frenchie_care_meta_nonce']) || !wp_verify_nonce($_POST['frenchie_care_meta_nonce'], 'frenchie_care_save_meta')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (isset($_POST['meta_description'])) {
        update_post_meta($post_id, 'meta_description', sanitize_textarea_field($_POST['meta_description']));
    }
    
    if (isset($_POST['frenchie_faqs'])) {
        $faqs = array();
        foreach ($_POST['frenchie_faqs'] as $faq) {
            if (!empty($faq['question']) && !empty($faq['answer'])) {
                $faqs[] = array(
                    'question' => sanitize_text_field($faq['question']),
                    'answer' => sanitize_textarea_field($faq['answer'])
                );
            }
        }
        update_post_meta($post_id, 'frenchie_faqs', $faqs);
    }
}
add_action('save_post', 'frenchie_care_save_meta_boxes');

// Add internal linking suggestions
function frenchie_care_internal_links($content) {
    if (!is_singular('post')) {
        return $content;
    }
    
    // Get related posts from the same category
    $categories = wp_get_post_categories(get_the_ID());
    if ($categories) {
        $args = array(
            'category__in' => $categories,
            'post__not_in' => array(get_the_ID()),
            'posts_per_page' => 3,
            'orderby' => 'rand'
        );
        $related_posts = new WP_Query($args);
        
        if ($related_posts->have_posts()) {
            $related_html = '<div class="related-posts"><h3>Related Frenchie Care Articles:</h3><ul>';
            while ($related_posts->have_posts()) {
                $related_posts->the_post();
                $related_html .= '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
            }
            $related_html .= '</ul></div>';
            $content .= $related_html;
            wp_reset_postdata();
        }
    }
    
    return $content;
}
add_filter('the_content', 'frenchie_care_internal_links', 30);