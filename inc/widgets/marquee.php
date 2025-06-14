<?php
/**
 * Vecutus Marquee Widget
 */
class Vecutus_Marquee_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'vecutus_marquee_widget',
            __('Vecutus Marquee', 'text_domain'),
            array(
                'description' => __('Displays three scrolling marquees with posts from selected categories', 'text_domain'),
            )
        );
    }

    public function form($instance) {
        $categories = get_categories(array('hide_empty' => false));
        
        // Default values
        $defaults = array(
            'category1' => 'exam date',
            'category2' => 'results',
            'category3' => 'admit card',
            'speed1' => 3,
            'speed2' => 4,
            'speed3' => 5
        );
        
        $instance = wp_parse_args((array) $instance, $defaults);
        ?>
        
        <div class="vecutus-marquee-widget-form">
            <p><strong>First Marquee:</strong></p>
            <p>
                <label for="<?php echo $this->get_field_id('category1'); ?>">Category:</label>
                <select class="widefat" id="<?php echo $this->get_field_id('category1'); ?>" name="<?php echo $this->get_field_name('category1'); ?>">
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo $category->slug; ?>" <?php selected($instance['category1'], $category->slug); ?>>
                            <?php echo esc_html($category->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('speed1'); ?>">Scroll Speed (1-10):</label>
                <input class="widefat" type="number" min="1" max="10" id="<?php echo $this->get_field_id('speed1'); ?>" name="<?php echo $this->get_field_name('speed1'); ?>" value="<?php echo esc_attr($instance['speed1']); ?>">
            </p>
            
            <p><strong>Second Marquee:</strong></p>
            <p>
                <label for="<?php echo $this->get_field_id('category2'); ?>">Category:</label>
                <select class="widefat" id="<?php echo $this->get_field_id('category2'); ?>" name="<?php echo $this->get_field_name('category2'); ?>">
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo $category->slug; ?>" <?php selected($instance['category2'], $category->slug); ?>>
                            <?php echo esc_html($category->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('speed2'); ?>">Scroll Speed (1-10):</label>
                <input class="widefat" type="number" min="1" max="10" id="<?php echo $this->get_field_id('speed2'); ?>" name="<?php echo $this->get_field_name('speed2'); ?>" value="<?php echo esc_attr($instance['speed2']); ?>">
            </p>
            
            <p><strong>Third Marquee:</strong></p>
            <p>
                <label for="<?php echo $this->get_field_id('category3'); ?>">Category:</label>
                <select class="widefat" id="<?php echo $this->get_field_id('category3'); ?>" name="<?php echo $this->get_field_name('category3'); ?>">
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo $category->slug; ?>" <?php selected($instance['category3'], $category->slug); ?>>
                            <?php echo esc_html($category->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </p>
            <p>
                <label for="<?php echo $this->get_field_id('speed3'); ?>">Scroll Speed (1-10):</label>
                <input class="widefat" type="number" min="1" max="10" id="<?php echo $this->get_field_id('speed3'); ?>" name="<?php echo $this->get_field_name('speed3'); ?>" value="<?php echo esc_attr($instance['speed3']); ?>">
            </p>
        </div>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['category1'] = sanitize_text_field($new_instance['category1']);
        $instance['category2'] = sanitize_text_field($new_instance['category2']);
        $instance['category3'] = sanitize_text_field($new_instance['category3']);
        $instance['speed1'] = min(max(1, absint($new_instance['speed1'])), 10);
        $instance['speed2'] = min(max(1, absint($new_instance['speed2'])), 10);
        $instance['speed3'] = min(max(1, absint($new_instance['speed3'])), 10);
        return $instance;
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        // Marquee 1
        $this->render_marquee($instance['category1'], $instance['speed1']);
        
        // Marquee 2
        $this->render_marquee($instance['category2'], $instance['speed2']);
        
        // Marquee 3
        $this->render_marquee($instance['category3'], $instance['speed3']);
        
        echo $args['after_widget'];
    }
    
    private function render_marquee($category_slug, $speed) {
        $custom_query = new WP_Query(array(
            'category_name' => $category_slug,
            'posts_per_page' => 3
        ));
        
        if ($custom_query->have_posts()) : ?>
            <div class="vecutus-marquee-container">
                <marquee class="vecutus-marquee" behavior="alternate" scrollamount="<?php echo esc_attr($speed); ?>" onmouseout="this.start();" onmouseover="this.stop();">
                    <?php while ($custom_query->have_posts()) : $custom_query->the_post(); ?>
                        <a href="<?php the_permalink(); ?>" target="_blank"><?php echo get_the_title(); ?> ||</a>
                    <?php endwhile; ?>
                </marquee>
            </div>
        <?php endif;
        
        wp_reset_postdata();
    }
}

// Register the widget
function register_vecutus_marquee_widget() {
    register_widget('Vecutus_Marquee_Widget');
}
add_action('widgets_init', 'register_vecutus_marquee_widget');

// Add CSS styling
function vecutus_marquee_styles() {
    ?>
    <style>
        
        .vecutus-marquee {
    font-size: 17px;
    color: #333;
    line-height: 1.3;
    font-weight: 500;
}
        
        .vecutus-marquee a {
            text-decoration: none;
            color: #0066cc;
        }
        
        .vecutus-marquee a:hover {
            color: #004499;
            text-decoration: underline;
        }
    </style>
    <?php
}
add_action('wp_head', 'vecutus_marquee_styles');