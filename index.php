<?php get_header(); ?>

<div class="<?php echo esc_attr(get_theme_mod('container_width', 'container')); ?>" >
    <div class="cont-padding">
       
            <?php if ( is_active_sidebar( 'home-widget' ) ) : ?>
    <div class="home-widget-area">
        <?php dynamic_sidebar( 'home-widget' ); ?>
    </div>
<?php endif; ?>
        
    </div>
</div>

<?php get_footer(); ?>