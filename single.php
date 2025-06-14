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

            <?php
            // Fetching the custom meta data with empty checks
            $recruitment_board = get_post_meta(get_the_ID(), 'recruitment_board', true);
            $advt_no = get_post_meta(get_the_ID(), 'advt_no', true);
            $total_post = get_post_meta(get_the_ID(), 'post_name', true);
            $qualification = get_post_meta(get_the_ID(), 'qualification', true);
            $last_date = get_post_meta(get_the_ID(), 'last_date', true);
            
            // Format the date safely
            $formatted_date = '';
            if (!empty($last_date)) {
                try {
                    $date = DateTime::createFromFormat('Y-m-d', $last_date);
                    if ($date) {
                        $formatted_date = $date->format('d-m-Y');
                    } else {
                        $formatted_date = $last_date; // Fallback to original if format fails
                    }
                } catch (Exception $e) {
                    $formatted_date = $last_date; // Fallback to original if exception occurs
                }
            }
            ?>

            <!-- Displaying the custom fields -->
            <div class="custom-meta-data">
                <table>
                    <tr class="pst-top">
                        <td colspan="2">
                            <h2 class="pst-title"><b><?php the_title(); ?></b></h2>
                            Posted on: <a href="#" style="color: red;"><?php echo get_the_date('F j, Y'); ?> | </a>
                            Author: <a href="#" style="color: red;"><?php the_author(); ?></a>
                        </td>
                    </tr>

                    <?php if (!empty($recruitment_board)) : ?>
                        <tr>
                            <td><strong>Recruitment Board:</strong></td>
                            <td><?php echo esc_html($recruitment_board); ?></td>
                        </tr>
                    <?php endif; ?>
                    
                    <?php if (!empty($advt_no)) : ?>
                        <tr>
                            <td><strong>Advt No:</strong></td>
                            <td><?php echo esc_html($advt_no); ?></td>
                        </tr>
                    <?php endif; ?>

                    <?php if (!empty($total_post)) : ?>
                        <tr>
                            <td><strong>Total Post:</strong></td>
                            <td><?php echo esc_html($total_post); ?></td>
                        </tr>
                    <?php endif; ?>

                    <?php if (!empty($qualification)) : ?>
                        <tr>
                            <td><strong>Qualification:</strong></td>
                            <td><?php echo esc_html($qualification); ?></td>
                        </tr>
                    <?php endif; ?>

                    <?php if (!empty($last_date)) : ?>
                        <tr>
                            <td><strong>Last Date:</strong></td>
                            <td><?php echo esc_html($formatted_date); ?></td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>

            <?php while (have_posts()) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>

                    <?php if (has_post_thumbnail()) : ?>
                        <div class="entry-thumbnail">
                            <?php the_post_thumbnail('large'); ?>
                        </div>
                    <?php endif; ?>

                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>

                    <div class="entry-tags">
                        <?php if (has_tag()) : ?>
                            <div class="post-tags">
                                <strong>Tags: </strong><?php the_tags('', ', '); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>

                <div class="post-navigation">
                    <?php
                    the_post_navigation(array(
                        'prev_text' => '← Previous Post',
                        'next_text' => 'Next Post →',
                    ));
                    ?>
                </div>


<fieldset>
    <legend>Related Post:-</legend>
    <div class="entry-content">
<?php
// Get the current post ID
$current_post_id = get_the_ID();

// Get the current post categories
$categories = get_the_category( $current_post_id );

if ( $categories ) {
    $category_ids = array();

    // Loop through each category and store the ID
    foreach ( $categories as $category ) {
        $category_ids[] = $category->term_id;
    }

    // WP_Query arguments to fetch related posts
    $related_posts_args = array(
        'category__in'   => $category_ids, // Fetch posts from the same categories
        'post__not_in'   => array( $current_post_id ), // Exclude current post
        'posts_per_page' => 10, // Number of related posts to display
        'ignore_sticky_posts' => 1 // Ignore sticky posts
    );

    $related_posts_query = new WP_Query( $related_posts_args );

    // Check if there are related posts
    if ( $related_posts_query->have_posts() ) {
        echo '<ul class="related-posts">';
        
        // Loop through related posts
        while ( $related_posts_query->have_posts() ) {
            $related_posts_query->the_post();
            
            // Get the post's publication date
            $post_date = get_the_date( 'U' ); // Unix timestamp
            $current_time = current_time( 'timestamp' );
            $days_difference = ( $current_time - $post_date ) / ( 60 * 60 * 24 ); // Convert seconds to days

            // Check if the post is less than 7 days old
            $new_tag = ( $days_difference < 7 ) ? '<span class="new-tag" style="color: red; animation: blinker 1s linear infinite;">new</span> ' : '';

            // Output the related post with the "new" tag if applicable
            echo '<li><a href="' . get_the_permalink() . '">' . get_the_title() . ' </a>' . $new_tag . '</li>';
        }
        
        echo '</ul>';
    } else {
        // If no related posts found, display a message
        echo '<p>No related posts found.</p>';
    }

    // Reset post data
    wp_reset_postdata();
}
?>
    </div>
</fieldset>

<style>
/* CSS for the blinking effect */
@keyframes blinker {
    50% { opacity: 0; }
}
</style>

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