<?php
/**
  Social Profile Buttons Widget
 */

class Social_Profile_Buttons_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'social_profile_buttons_widget',
            __('Vecutus Social Buttons', 'text_domain'),
            array('description' => __('A widget to display WhatsApp and Telegram group join buttons.', 'text_domain'))
        );

        // Enqueue Font Awesome and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
    }

    public function enqueue_styles() {
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
        wp_add_inline_style('font-awesome', '
            .social-profile-widget {
                margin: 8px 0;
                clear: both;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }
            .group-card {
                flex: 1;
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px 15px;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                transition: transform 0.2s ease-in-out;
                min-width: 200px;
            }
            .group-card:hover {
                transform: translateY(-2px);
            }
            .whatsapp-card {
                background: linear-gradient(135deg, #25d366 0%, #20b35a 100%);
                color: white;
            }
            .telegram-card {
                background: linear-gradient(135deg, #0088cc 0%, #004F7A 100%);
                color: white;
            }
            .group-card span {
                display: flex;
                align-items: center;
            }
            .group-card i {
                font-size: 20px;
            }
            .group-card span span {
                font-weight: 600;
                margin-left: 8px;
                font-size: 0.9rem;
            }
            .group-card a {
                text-decoration: none;
                color: white;
                background: rgba(255,255,255,0.2);
                padding: 6px 12px;
                border-radius: 20px;
                font-size: 0.85rem;
                font-weight: 500;
                transition: background 0.3s;
                display: flex;
                align-items: center;
                gap: 5px;
            }
            .group-card a:hover {
                background: rgba(255,255,255,0.3);
            }
            .group-card a i {
                font-size: 16px;
            }
        ');
    }

    public function enqueue_admin_styles() {
        wp_enqueue_style('social-profile-admin', plugins_url('admin-style.css', __FILE__), array(), '1.1');
        wp_add_inline_style('social-profile-admin', '
            .social-profile-widget input[type="url"] {
                width: 100%;
                padding: 8px;
                margin-bottom: 10px;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
            .social-profile-widget label {
                display: block;
                margin-bottom: 5px;
                font-weight: bold;
            }
        ');
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];

        $whatsapp_link = !empty($instance['whatsapp_link']) ? esc_url($instance['whatsapp_link']) : '#';
        $telegram_link = !empty($instance['telegram_link']) ? esc_url($instance['telegram_link']) : '#';

        ?>
        <div class="social-profile-widget">
            <div class="group-card whatsapp-card">
                <span><i class="fab fa-whatsapp"></i>
                    <span>WhatsApp Group</span>
                </span>
                <a class="seoquake-nofollow" href="<?php echo esc_url($whatsapp_link); ?>" rel="nofollow noopener noreferrer" target="_blank">
                    <i class="fab fa-whatsapp"></i> Join Now
                </a>
            </div>

            <div class="group-card telegram-card">
                <span><i class="fab fa-telegram"></i>
                    <span>Telegram Group</span>
                </span>
                <a class="seoquake-nofollow" href="<?php echo esc_url($telegram_link); ?>" rel="nofollow noopener noreferrer" target="_blank">
                    <i class="fab fa-telegram"></i> Join Now
                </a>
            </div>
        </div>
        <?php

        echo $args['after_widget'];
    }

    public function form($instance) {
        $whatsapp_link = !empty($instance['whatsapp_link']) ? $instance['whatsapp_link'] : '';
        $telegram_link = !empty($instance['telegram_link']) ? $instance['telegram_link'] : '';
        ?>
        <div class="social-profile-widget">
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('whatsapp_link')); ?>"><?php _e('WhatsApp Group Link:', 'text_domain'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('whatsapp_link')); ?>" name="<?php echo esc_attr($this->get_field_name('whatsapp_link')); ?>" type="url" value="<?php echo esc_attr($whatsapp_link); ?>" placeholder="Enter WhatsApp group link">
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('telegram_link')); ?>"><?php _e('Telegram Group Link:', 'text_domain'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('telegram_link')); ?>" name="<?php echo esc_attr($this->get_field_name('telegram_link')); ?>" type="url" value="<?php echo esc_attr($telegram_link); ?>" placeholder="Enter Telegram group link">
            </p>
        </div>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['whatsapp_link'] = !empty($new_instance['whatsapp_link']) ? esc_url_raw($new_instance['whatsapp_link']) : '';
        $instance['telegram_link'] = !empty($new_instance['telegram_link']) ? esc_url_raw($new_instance['telegram_link']) : '';
        return $instance;
    }
}

function register_social_profile_buttons_widget() {
    register_widget('Social_Profile_Buttons_Widget');
}
add_action('widgets_init', 'register_social_profile_buttons_widget');
?>