<?php
/**
 * Job Board Posts Widget
 * Displays posts from selected categories with custom fields
 */
class Job_Board_Posts_Widget extends WP_Widget {

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'job_board_posts_widget',
            __('Vecutus Post Box', 'text_domain'),
            array(
                'description' => __('Displays posts from selected categories with custom fields', 'text_domain'),
            )
        );
    }

    /**
     * Widget form creation
     */
    public function form($instance) {
        $categories = get_categories(array('hide_empty' => false));
        $selected_cats = isset($instance['categories']) ? (array)$instance['categories'] : array();
        $post_counts = isset($instance['post_counts']) ? (array)$instance['post_counts'] : array();
        
        // Default values
        if (empty($selected_cats)) {
            $selected_cats = array('');
            $post_counts = array(5);
        }
        ?>
        
        <div class="jbp-widget-form">
            <p><strong><?php _e('Select categories to display:', 'text_domain'); ?></strong></p>
            
            <div class="jbp-category-repeater">
                <?php foreach ($selected_cats as $index => $cat_id) : ?>
                    <div class="jbp-category-group">
                        <p>
                            <label for="<?php echo $this->get_field_id('categories'); ?>_<?php echo $index; ?>">
                                <?php _e('Category:', 'text_domain'); ?>
                            </label>
                            <select class="widefat" 
                                    id="<?php echo $this->get_field_id('categories'); ?>_<?php echo $index; ?>" 
                                    name="<?php echo $this->get_field_name('categories'); ?>[]">
                                <option value=""><?php _e('-- Select Category --', 'text_domain'); ?></option>
                                <?php foreach ($categories as $category) : ?>
                                    <option value="<?php echo $category->term_id; ?>" <?php selected($cat_id, $category->term_id); ?>>
                                        <?php echo esc_html($category->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </p>
                        
                        <p>
                            <label for="<?php echo $this->get_field_id('post_counts'); ?>_<?php echo $index; ?>">
                                <?php _e('Number of posts to show:', 'text_domain'); ?>
                            </label>
                            <input class="widefat" 
                                   id="<?php echo $this->get_field_id('post_counts'); ?>_<?php echo $index; ?>" 
                                   name="<?php echo $this->get_field_name('post_counts'); ?>[]" 
                                   type="number" 
                                   min="1" 
                                   value="<?php echo isset($post_counts[$index]) ? esc_attr($post_counts[$index]) : 5; ?>">
                        </p>
                        
                        <button type="button" class="button jbp-remove-category"><?php _e('Remove', 'text_domain'); ?></button>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <button type="button" class="button jbp-add-category"><?php _e('Add Another Category', 'text_domain'); ?></button>
        </div>
        
        <script>
            jQuery(document).ready(function($) {
                $(document).on('click', '.jbp-add-category', function() {
                    var $repeater = $(this).siblings('.jbp-category-repeater');
                    var $clone = $repeater.find('.jbp-category-group').first().clone();
                    
                    // Clear selected value and reset post count
                    $clone.find('select').val('');
                    $clone.find('input[type="number"]').val('5');
                    
                    $repeater.append($clone);
                });
                
                $(document).on('click', '.jbp-remove-category', function() {
                    if ($(this).closest('.jbp-category-repeater').find('.jbp-category-group').length > 1) {
                        $(this).closest('.jbp-category-group').remove();
                    } else {
                        alert('<?php _e('You need to have at least one category.', 'text_domain'); ?>');
                    }
                });
            });
        </script>
        
        <style>
            .jbp-category-group {
                padding: 10px;
                margin-bottom: 10px;
                border: 1px solid #ddd;
                position: relative;
            }
            .jbp-remove-category {
                margin-top: 5px;
            }
        </style>
        <?php
    }

    /**
     * Widget update
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['categories'] = !empty($new_instance['categories']) ? array_map('absint', $new_instance['categories']) : array();
        $instance['post_counts'] = !empty($new_instance['post_counts']) ? array_map('absint', $new_instance['post_counts']) : array();
        
        // Ensure counts are at least 1
        foreach ($instance['post_counts'] as &$count) {
            $count = max(1, $count);
        }
        
        return $instance;
    }

    /**
     * Widget display
     */
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        $categories = isset($instance['categories']) ? $instance['categories'] : array();
        $post_counts = isset($instance['post_counts']) ? $instance['post_counts'] : array();
        
        // Enqueue Font Awesome if not already loaded
        if (!wp_style_is('font-awesome', 'enqueued')) {
            wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');
        }
        ?>
        
        <div class="jbp-container">
            <?php foreach ($categories as $index => $cat_id) : 
                if (!$cat_id) continue;
                $category = get_term($cat_id, 'category');
                if (is_wp_error($category) || !$category) continue;
                $is_latest_jobs = (strtolower($category->name) === 'latest jobs' || $category->slug === 'latest-jobs');
                $post_count = isset($post_counts[$index]) && $post_counts[$index] > 0 ? $post_counts[$index] : 10;
                ?>
                <div class="jbp-column">
                    <h2><?php echo esc_html($category->name); ?></h2>
                    <div class="jbp-posts">
                        <?php
                        $args = array(
                            'post_type' => 'post',
                            'cat' => $cat_id,
                            'posts_per_page' => $post_count,
                        );
                        $query = new WP_Query($args);
                        if ($query->have_posts()) :
                            while ($query->have_posts()) : $query->the_post();
                                ?>
                                <div class="jbp-post">
                                    <p><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
                                    <div class="jbp-custom-fields">
                                        <?php
                                        $field_outputs = array();
                                        $fields_to_show = $is_latest_jobs ? array('status', 'last_date') : array('status', 'post_date');

                                        foreach ($fields_to_show as $field_name) :
                                            $field_value = '';
                                            $field_label = '';
                                            $icon = 'fa-circle-info';

                                            if ($field_name === 'post_date') {
                                                $field_value = get_the_date(get_option('date_format'));
                                                $field_label = 'Published';
                                                $icon = 'fa-calendar';
                                            } else {
                                                // Get custom field value without ACF
                                                $field_value = get_post_meta(get_the_ID(), $field_name, true);
                                                if (empty($field_value)) continue;
                                                
                                                // Create human-readable label
                                                $field_label = ucwords(str_replace('_', ' ', $field_name));
                                                
                                                if (is_array($field_value)) {
                                                    $field_value = implode(', ', array_filter((array)$field_value));
                                                }
                                                
                                                // Set appropriate icon
                                                if (stripos($field_name, 'date') !== false) {
                                                    $icon = 'fa-calendar';
                                                } elseif (stripos($field_name, 'status') !== false) {
                                                    $icon = 'fa-tag';
                                                }
                                            }

                                            if (!empty($field_value)) {
                                                $field_outputs[] = '<span class="jbp-field"><i class="fa ' . esc_attr($icon) . '"></i> <strong>' . esc_html($field_label) . ':</strong> ' . esc_html($field_value) . '</span>';
                                            }
                                        endforeach;

                                        if (!empty($field_outputs)) {
                                            echo implode(' <span class="jbp-field-sep">|</span> ', $field_outputs);
                                        }
                                        if ($is_latest_jobs && !empty($field_outputs)) {
                                            echo '<a href="' . esc_url(get_permalink()) . '" class="jbp-apply-button">Apply Now</a>';
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?php
                            endwhile;
                            wp_reset_postdata();
                        else :
                            echo '<p>No posts found.</p>';
                        endif;
                        ?>
                    </div>
                    <a href="<?php echo esc_url(get_category_link($cat_id)); ?>" class="jbp-archive-button">View All</a>
                </div>
            <?php endforeach; ?>
        </div>
        
        <style>
	
            .jbp-container {
                display: flex;
                flex-wrap: wrap;
                gap: 5px;
                margin-bottom: 10px;
            }

            .jbp-column {
                flex: 1;
                min-width: 300px;
                border: 1px solid #e0e0e0;
                border-radius: 8px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                padding: 10px;
                background: #fff;
                transition: transform 0.3s ease;
            }

            .jbp-column h2 {
                font-size: 1.5em;
                margin: 0 0 10px;
                text-align: center;
                border-radius: 4px;
                font-weight: 500;
                color: #fff;
            }

            .jbp-posts {
                margin-bottom: 15px;
            }

            .jbp-post {
                padding: 10px;
                margin-bottom: 10px;
                border: 1px solid #f0f0f0;
                border-radius: 4px;
                background: #fafafa;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
                transition: all 0.3s ease;
            }

            .jbp-post:hover {
                border-color: #d0d0d0;
                box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
                transform: translateY(-2px);
            }

            .jbp-post p {
                font-size: 0.9em;
                margin: 0px;
                font-weight: 500;
                line-height: 1.1;
            }

            .jbp-post p a {
                text-decoration: none;
                transition: color 0.3s ease;
                font-weight: 600;
                font-size: 17px;
            }

            .jbp-custom-fields {
                font-size: 0.7em;
                color: #444;
                display: flex;
                align-items: center;
                gap: 5px;
                flex-wrap: wrap;
                justify-content: left;
            }

            .jbp-custom-fields .jbp-field {
                display: inline-flex;
                align-items: center;
                gap: 5px;
            }

            .jbp-custom-fields .jbp-field strong {
                font-weight: 700;
                margin-right: 4px;
            }

            .jbp-custom-fields .jbp-field i {
                color: #0073aa;
                font-size: 0.9em;
                margin-right: 4px;
            }

            .jbp-field-sep {
                color: #ccc;
                font-size: 0.8em;
            }

            .jbp-apply-button {
                padding: 2px 5px;
                font-size: 0.85em;
                text-decoration: none;
                border-radius: 4px;
                transition: filter 0.3s ease;
                white-space: nowrap;
                background: #2563eb;
                color: #fff;
            }

            .jbp-archive-button {
                display: block;
                text-align: center;
                padding: 5px 20px;
                text-decoration: none;
                border-radius: 4px;
                margin-top: 10px;
                transition: filter 0.3s ease;
                color: #fff;
            }
            .jbp-archive-button:hover {
                filter: brightness(90%);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }

            @media (max-width: 768px) {
				.jbp-post p {font-size: 0.9em;}
				
                .jbp-column {
                    min-width: 100%;
                }

                .jbp-custom-fields {
                    align-items: flex-start;
                    gap: 5px;
                }

                .jbp-field-sep {
                    display: none;
                }

                .jbp-apply-button {
                    margin-top: 5px;
                    align-self: flex-start;
                }
            }
        </style>
        <?php
        echo $args['after_widget'];
    }
}

// Register the widget
function register_job_board_posts_widget() {
    register_widget('Job_Board_Posts_Widget');
}
add_action('widgets_init', 'register_job_board_posts_widget');