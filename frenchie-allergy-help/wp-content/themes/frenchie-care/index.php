<?php
/**
 * The main template file
 *
 * @package FrenchieCare
 */

get_header(); ?>

<div class="site-content">
    <div class="container">
        <div class="content-area">
            <main id="main" class="site-main">
                <?php if (is_home() && !is_front_page()) : ?>
                    <header>
                        <h1 class="page-title"><?php single_post_title(); ?></h1>
                    </header>
                <?php endif; ?>

                <?php if (have_posts()) : ?>
                    <div class="posts-grid">
                        <?php while (have_posts()) : the_post(); ?>
                            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="post-thumbnail">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('medium'); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <header class="entry-header">
                                    <h2 class="entry-title">
                                        <a href="<?php the_permalink(); ?>" rel="bookmark">
                                            <?php the_title(); ?>
                                        </a>
                                    </h2>
                                    
                                    <div class="entry-meta">
                                        <span class="posted-on">
                                            <?php echo get_the_date(); ?>
                                        </span>
                                        <span class="byline">
                                            by <?php the_author(); ?>
                                        </span>
                                    </div>
                                </header>
                                
                                <div class="entry-summary">
                                    <?php the_excerpt(); ?>
                                </div>
                                
                                <footer class="entry-footer">
                                    <a href="<?php the_permalink(); ?>" class="read-more">
                                        Read More →
                                    </a>
                                </footer>
                            </article>
                        <?php endwhile; ?>
                    </div>
                    
                    <div class="pagination">
                        <?php
                        the_posts_pagination(array(
                            'prev_text' => '← Previous',
                            'next_text' => 'Next →',
                        ));
                        ?>
                    </div>
                <?php else : ?>
                    <div class="no-results">
                        <h2>Nothing Found</h2>
                        <p>It seems we can't find what you're looking for. Perhaps searching can help.</p>
                        <?php get_search_form(); ?>
                    </div>
                <?php endif; ?>
            </main>
        </div>
        
        <?php get_sidebar(); ?>
    </div>
</div>

<?php get_footer(); ?>