<?php
/**
 * The template for displaying all single posts
 *
 * @package FrenchieCare
 */

get_header(); ?>

<div class="site-content">
    <div class="container">
        <div class="content-area">
            <main id="main" class="site-main">
                <?php while (have_posts()) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">
                            <h1 class="entry-title"><?php the_title(); ?></h1>
                            
                            <div class="entry-meta">
                                <span class="posted-on">
                                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                        <?php echo get_the_date(); ?>
                                    </time>
                                </span>
                                <span class="byline">
                                    by <?php the_author(); ?>
                                </span>
                                <span class="cat-links">
                                    in <?php the_category(', '); ?>
                                </span>
                            </div>
                        </header>
                        
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <?php the_post_thumbnail('large'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="entry-content">
                            <?php the_content(); ?>
                            
                            <?php
                            wp_link_pages(array(
                                'before' => '<div class="page-links">Pages: ',
                                'after' => '</div>',
                            ));
                            ?>
                        </div>
                        
                        <footer class="entry-footer">
                            <?php if (has_tag()) : ?>
                                <div class="tags-links">
                                    <span class="tags-label">Tags:</span>
                                    <?php the_tags('', ', '); ?>
                                </div>
                            <?php endif; ?>
                        </footer>
                    </article>
                    
                    <nav class="post-navigation">
                        <h2 class="screen-reader-text">Post navigation</h2>
                        <div class="nav-links">
                            <?php
                            $prev_post = get_previous_post();
                            $next_post = get_next_post();
                            ?>
                            
                            <?php if ($prev_post) : ?>
                                <div class="nav-previous">
                                    <a href="<?php echo get_permalink($prev_post); ?>">
                                        <span class="nav-subtitle">← Previous</span>
                                        <span class="nav-title"><?php echo get_the_title($prev_post); ?></span>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($next_post) : ?>
                                <div class="nav-next">
                                    <a href="<?php echo get_permalink($next_post); ?>">
                                        <span class="nav-subtitle">Next →</span>
                                        <span class="nav-title"><?php echo get_the_title($next_post); ?></span>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </nav>
                    
                    <?php
                    // If comments are open or we have at least one comment, load up the comment template.
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;
                    ?>
                <?php endwhile; ?>
            </main>
        </div>
        
        <?php get_sidebar(); ?>
    </div>
</div>

<?php get_footer(); ?>