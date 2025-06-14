<?php get_header(); ?>

<div class="<?php echo esc_attr(get_theme_mod('container_width', 'container')); ?>">
    <main id="main" class="site-main cont-padding">
        <div class="content-area">
			 <style>
                .pst-title {margin-bottom: 0px;}
                .pst-top {background: #ededed;}
                .custom-meta-data table {width: 100%; margin-bottom: 20px;}
                .custom-meta-data td {padding: 8px; vertical-align: top;}
                @media screen and (max-width: 480px) {
                    .pst-title {font-size: 20px;}
                }
				
            </style>
            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('single-page'); ?>>
                    <header class="entry-header">
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                    </header>

                    <?php if (has_post_thumbnail()) : ?>
                        <div class="entry-thumbnail">
                            <?php the_post_thumbnail('large'); ?>
                        </div>
                    <?php endif; ?>

                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                </article>

                <?php
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
                ?>
            <?php endwhile; ?>
        </div>
        
        <?php get_sidebar(); ?>
    </main>
</div>

<?php get_footer(); ?>