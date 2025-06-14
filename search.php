<?php get_header(); ?>
<style>
@media screen and (max-width: 480px) {
    .table-erc {
        font-size: 10px;
    }
}
</style>
<div class="<?php echo esc_attr(get_theme_mod('container_width', 'container')); ?>">
    <main id="main" class="site-main cont-padding">
        <div class="content-area">
            <header class="page-header">
                <h1 class="page-title">
                    <?php printf(esc_html__('Search Results for: %s', 'your-text-domain'), '<span>' . get_search_query() . '</span>'); ?>
                </h1>
            </header>

            <?php if (have_posts()) : ?>
                <div class="arc-tab">    
                    <table class="table-erc">
                        <thead>
                            <tr>
                                <th>Post Date</th>
                                <th>Recruitment Board</th>
                                <th>Post Name</th>
                                <th>Total Post</th>
                                <th>Qualification</th>
                                <th>Last Date</th>
                                <th>More Information</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while (have_posts()) : the_post(); ?>
                                <tr>
                                    <td><?php echo get_the_date(); ?></td>
                                    <td><?php echo esc_html(get_post_meta(get_the_ID(), 'recruitment_board', true) ?: '-'); ?></td>
                                    <td><b><?php the_title(); ?></b></td>
                                    <td><?php echo esc_html(get_post_meta(get_the_ID(), 'post_name', true) ?: '-'); ?></td>
                                    <td><?php echo esc_html(get_post_meta(get_the_ID(), 'qualification', true) ?: '-'); ?></td>
                                    <td>
                                        <?php 
                                        $last_date = get_post_meta(get_the_ID(), 'last_date', true);
                                        if (!empty($last_date)) {
                                            try {
                                                $date = DateTime::createFromFormat('Y-m-d', $last_date);
                                                echo $date ? esc_html($date->format('d-m-Y')) : esc_html($last_date);
                                            } catch (Exception $e) {
                                                echo esc_html($last_date); // Fallback to original format if conversion fails
                                            }
                                        } else {
                                            echo '-';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <strong>
                                            <a href="<?php the_permalink(); ?>" rel="nofollow noopener" target="_blank">
                                                Get Details..
                                            </a>
                                        </strong>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="pagination">
                    <?php
                    the_posts_pagination(array(
                        'mid_size' => 2,
                        'prev_text' => __('« Previous', 'textdomain'),
                        'next_text' => __('Next »', 'textdomain'),
                    ));
                    ?>
                </div>
            <?php else : ?>
                <div class="no-results">
                    <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'your-text-domain'); ?></p>
                    <?php get_search_form(); ?>
                </div>
            <?php endif; ?>
        </div>
        
        <?php get_sidebar(); ?>
    </main>
</div>

<?php get_footer(); ?>