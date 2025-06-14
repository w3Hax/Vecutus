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
                    <?php
                    if (is_category()) {
                        single_cat_title();
                    } elseif (is_tag()) {
                        single_tag_title();
                    } elseif (is_author()) {
                        printf(__('Author: %s', 'vecutus'), '<span class="vcard">' . get_the_author() . '</span>');
                    } elseif (is_date()) {
                        _e('Archives', 'vecutus');
                    } else {
                        _e('Archives', 'vecutus');
                    }
                    ?>
                </h1>
                <?php
                $description = get_the_archive_description();
                if ($description) {
                    echo '<div class="archive-description">' . $description . '</div>';
                }
                ?>
            </header>

            <?php
            $main_category_id = get_queried_object_id(); // Get the current main category ID

            // Display the main category's posts
            $paged_main = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $query_main = new WP_Query(array(
                'cat' => $main_category_id,
                'posts_per_page' => 10, // Adjust as needed
                'paged' => $paged_main
            ));

            if ($query_main->have_posts()) : ?>
                <div class="arc-tab">    
                    <table class="table-erc">
                        <tbody>
                            <tr>
                                <th>Post Date</th>
                                <th>Post Name</th>
                                <th>Total Post</th>
                                <th>Qualification</th>
                                <th>Last Date</th>
                                <th>More Information</th>
                            </tr>
                            <?php while ($query_main->have_posts()) : $query_main->the_post(); ?>
                                <tr>
                                    <td><?php echo get_the_date(); ?></td>
                                    <td><b><?php echo get_the_title(); ?></b></td>
                                    <td><?php echo get_post_meta(get_the_ID(), 'post_name', true) ?: '-'; ?></td>
                                    <td><?php echo get_post_meta(get_the_ID(), 'qualification', true) ?: '-'; ?></td>
                                    <td>
                                        <?php 
                                        $last_date = get_post_meta(get_the_ID(), 'last_date', true);
                                        if (!empty($last_date)) {
                                            try {
                                                $date = DateTime::createFromFormat('Y-m-d', $last_date);
                                                echo $date ? $date->format('d-m-Y') : $last_date;
                                            } catch (Exception $e) {
                                                echo $last_date;
                                            }
                                        } else {
                                            echo '-';
                                        }
                                        ?>
                                    </td>
                                    <td><strong><a href="<?php the_permalink(); ?>" rel="nofollow noopener" target="_blank">Get Details..</a></strong></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>    
                <div class="pagination">
                    <?php
                    // Custom pagination for the main category
                    echo paginate_links(array(
                        'total' => $query_main->max_num_pages,
                        'current' => $paged_main,
                        'mid_size' => 2,
                        'prev_text' => __('« Previous', 'textdomain'),
                        'next_text' => __('Next »', 'textdomain'),
                    ));
                    ?>
                </div>
            <?php else : ?>
                <p>No posts found in this category.</p>
            <?php endif;
            wp_reset_postdata(); // Reset the main query
            ?>

            <hr>

            <?php
            // Get all child categories of the main category
            $child_categories = get_categories(array(
                'child_of' => $main_category_id,
                'hide_empty' => true,
            ));

            if ($child_categories) : ?>
                <?php foreach ($child_categories as $child_category) : ?>
                    <h2 class="arc-title"><?php echo esc_html($child_category->name); ?></h2>
                    <?php
                    $paged_child = (get_query_var('paged')) ? get_query_var('paged') : 1;
                    $query_child = new WP_Query(array(
                        'cat' => $child_category->term_id,
                        'posts_per_page' => 10, // Adjust as needed
                        'paged' => $paged_child
                    ));

                    if ($query_child->have_posts()) : ?>
                        <div class="arc-tab">
                            <table class="table-erc">
                                <tbody>
                                    <tr>
                                        <th>Post Date</th>
                                        <th>Post Name</th>
                                        <th>Total Post</th>
                                        <th>Qualification</th>
                                        <th>Last Date</th>
                                        <th>More Information</th>
                                    </tr>
                                    <?php while ($query_child->have_posts()) : $query_child->the_post(); ?>
                                        <tr>
                                            <td><?php echo get_the_date(); ?></td>
                                            <td><b><?php echo get_the_title(); ?></b></td>
                                            <td><?php echo get_post_meta(get_the_ID(), 'post_name', true) ?: '-'; ?></td>
                                            <td><?php echo get_post_meta(get_the_ID(), 'qualification', true) ?: '-'; ?></td>
                                            <td>
                                                <?php 
                                                $last_date = get_post_meta(get_the_ID(), 'last_date', true);
                                                if (!empty($last_date)) {
                                                    try {
                                                        $date = DateTime::createFromFormat('Y-m-d', $last_date);
                                                        echo $date ? $date->format('d-m-Y') : $last_date;
                                                    } catch (Exception $e) {
                                                        echo $last_date;
                                                    }
                                                } else {
                                                    echo '-';
                                                }
                                                ?>
                                            </td>
                                            <td><strong><a href="<?php the_permalink(); ?>" rel="nofollow noopener" target="_blank">Get Details..</a></strong></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination for child categories is removed -->
                    <?php else : ?>
                        <p>No posts found in this category.</p>
                    <?php endif; ?>
                    <?php wp_reset_postdata(); // Reset the child category query ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <?php get_sidebar(); ?>
    </main>
</div>

<?php get_footer(); ?>