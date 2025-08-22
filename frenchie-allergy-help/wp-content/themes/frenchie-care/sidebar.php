<?php
/**
 * The sidebar containing the main widget area
 *
 * @package FrenchieCare
 */

if (!is_active_sidebar('sidebar-1')) {
    return;
}
?>

<aside id="secondary" class="widget-area sidebar">
    <?php dynamic_sidebar('sidebar-1'); ?>
    
    <!-- Lead Capture Widget -->
    <div class="widget lead-capture-widget">
        <h3 class="widget-title">Free Frenchie Allergy Guide</h3>
        <div class="lead-capture">
            <p>Get our comprehensive guide to managing your Frenchie's allergies!</p>
            <form action="/subscribe" method="post" class="lead-capture-form">
                <input type="email" name="email" placeholder="Your email" required>
                <button type="submit">Get Guide</button>
            </form>
        </div>
    </div>
    
    <!-- Popular Posts Widget -->
    <div class="widget popular-posts-widget">
        <h3 class="widget-title">Popular Frenchie Care Articles</h3>
        <?php
        $popular_posts = new WP_Query(array(
            'posts_per_page' => 5,
            'meta_key' => 'post_views_count',
            'orderby' => 'meta_value_num',
            'order' => 'DESC'
        ));
        
        if ($popular_posts->have_posts()) : ?>
            <ul>
                <?php while ($popular_posts->have_posts()) : $popular_posts->the_post(); ?>
                    <li>
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php endif;
        wp_reset_postdata();
        ?>
    </div>
    
    <!-- Categories Widget -->
    <div class="widget categories-widget">
        <h3 class="widget-title">Frenchie Care Topics</h3>
        <ul>
            <?php wp_list_categories(array(
                'title_li' => '',
                'show_count' => true,
                'orderby' => 'count',
                'order' => 'DESC'
            )); ?>
        </ul>
    </div>
    
    <!-- Resources Widget -->
    <div class="widget resources-widget">
        <h3 class="widget-title">Frenchie Resources</h3>
        <ul>
            <li><a href="/frenchie-allergy-toolkit">Allergy Toolkit (€19)</a></li>
            <li><a href="/elimination-diet-guide">Elimination Diet Guide</a></li>
            <li><a href="/weekly-care-checklist">Weekly Care Checklist</a></li>
            <li><a href="/vet-questions">Questions for Your Vet</a></li>
        </ul>
    </div>
</aside>