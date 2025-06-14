<?php
/*
 Vecutus Color Box Widget
*/

if (!defined('ABSPATH')) exit;

class Vecutus_Color_Box_Widget extends WP_Widget {
    private $default_colors = [
        '#3498db', '#e74c3c', '#2ecc71', '#f39c12',
        '#9b59b6', '#1abc9c', '#d35400', '#34495e'
    ];

    public function __construct() {
        parent::__construct(
            'vecutus_color_box_widget',
            'Vecutus Color Box',
            ['description' => 'Displays latest 8 posts in colorful grid with customizable box colors.']
        );

        add_action('admin_enqueue_scripts', [$this, 'admin_assets']);
        add_action('wp_enqueue_scripts', [$this, 'frontend_assets']);
    }

    public function admin_assets($hook) {
        if ('widgets.php' !== $hook) return;
        
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        
        // Inline script for color picker initialization
        wp_add_inline_script('wp-color-picker', '
            jQuery(document).ready(function($) {
                function initColorPickers() {
                    $(".vecutus-color-picker").wpColorPicker();
                }
                
                // Initialize on load
                initColorPickers();
                
                // Reinitialize when widget is added or updated
                $(document).on("widget-added widget-updated", function(e, widget) {
                    initColorPickers();
                });
            });
        ');
    }

    public function frontend_assets() {
        $css = '
        .vecutus-posts-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 5px;
            margin: 0 auto;
            max-width: 100%;
            padding-bottom: 10px;
        }
        .vecutus-post-box {
            border-radius: 5px;
            padding: 7px;
            color: white;
            text-shadow: 0 1px 1px rgba(0,0,0,0.3);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.2s;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .vecutus-post-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .vecutus-post-title {
            font-size: 16px;
            margin: 0 0 10px 0;
            line-height: 1.3;
        }
        .vecutus-post-title a {
            color: inherit;
            text-decoration: none;
        }
        .vecutus-post-meta {
            font-size: 12px;
            opacity: 0.9;
            margin-top: auto;
        }
        @media (max-width: 768px) {
            .vecutus-posts-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }';
        
        wp_add_inline_style('wp-block-library', $css);
    }

    public function form($instance) {
        $title = $instance['title'] ?? 'Latest Posts';
        $colors = $instance['colors'] ?? $this->default_colors;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
                   name="<?php echo $this->get_field_name('title'); ?>" 
                   type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>Box Colors:</p>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
            <?php for ($i = 0; $i < 8; $i++): ?>
                <div>
                    <label for="<?php echo $this->get_field_id('colors'); ?>-<?php echo $i; ?>">
                        Box <?php echo $i + 1; ?>:
                    </label>
                    <input class="vecutus-color-picker widefat" 
                           id="<?php echo $this->get_field_id('colors'); ?>-<?php echo $i; ?>" 
                           name="<?php echo $this->get_field_name('colors'); ?>[]" 
                           type="text" value="<?php echo esc_attr($colors[$i]); ?>">
                </div>
            <?php endfor; ?>
        </div>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = ['title' => sanitize_text_field($new_instance['title'] ?? '')];
        
        if (!empty($new_instance['colors']) && is_array($new_instance['colors'])) {
            $instance['colors'] = [];
            foreach ($new_instance['colors'] as $color) {
                $instance['colors'][] = $this->sanitize_hex_color($color);
            }
        } else {
            $instance['colors'] = $this->default_colors;
        }
        
        return $instance;
    }

    private function sanitize_hex_color($color) {
        return preg_match('/^#([A-Fa-f0-9]{3}){1,2}$/', $color) ? $color : '#ffffff';
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];
        
     
        
        $colors = $instance['colors'] ?? $this->default_colors;
        $posts = new WP_Query([
            'posts_per_page' => 8,
            'post_status' => 'publish',
            'ignore_sticky_posts' => true
        ]);
        
        if ($posts->have_posts()) {
            echo '<div class="vecutus-posts-grid">';
            $count = 0;
            
            while ($posts->have_posts() && $count < 8) {
                $posts->the_post();
                $color = $colors[$count] ?? $this->default_colors[$count];
                ?>
                <div class="vecutus-post-box" style="background-color: <?php echo esc_attr($color); ?>">
                    <h3 class="vecutus-post-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>
                </div>
                <?php
                $count++;
            }
            
            echo '</div>';
            wp_reset_postdata();
        } else {
            echo '<p>No posts found.</p>';
        }
        
        echo $args['after_widget'];
    }
}

add_action('widgets_init', function() {
    register_widget('Vecutus_Color_Box_Widget');
});