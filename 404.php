<?php get_header(); ?>

<div class="<?php echo esc_attr(get_theme_mod('container_width', 'container')); ?>">
   
        <div class="content-area">
            <section class="error-404 not-found">
                <header class="page-header">
                    <h1 class="page-title">404 - Page Not Found</h1>
                </header>

                <div class="page-content">
                    <p>It looks like nothing was found at this location. Maybe try a search?</p>
                    <?php get_search_form(); ?>
                </div>
            </section>
        </div>
   
</div>

<?php get_footer(); ?>