    </div><!-- #content -->
    
    <footer id="colophon" class="site-footer">
        <div class="container">
            <?php if (is_active_sidebar('footer-widgets')) : ?>
                <div class="footer-widgets">
                    <?php dynamic_sidebar('footer-widgets'); ?>
                </div>
            <?php endif; ?>
            
            <div class="footer-bottom">
                <div class="site-info">
                    <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
                    <p>
                        <a href="<?php echo esc_url(home_url('/disclaimer')); ?>">Medical Disclaimer</a> | 
                        <a href="<?php echo esc_url(home_url('/privacy-policy')); ?>">Privacy Policy</a> | 
                        <a href="<?php echo esc_url(home_url('/affiliate-disclosure')); ?>">Affiliate Disclosure</a>
                    </p>
                </div>
                
                <?php if (has_nav_menu('footer')) : ?>
                    <nav class="footer-navigation">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'footer',
                            'menu_id' => 'footer-menu',
                            'depth' => 1,
                        ));
                        ?>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>