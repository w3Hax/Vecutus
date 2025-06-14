<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Theme setup
function vecutus_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('custom-logo');
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'vecutus'),
        'secondary' => __('Secondary Menu', 'vecutus'),
        'footer' => __('Footer Menu', 'vecutus'),
    ));
}
add_action('after_setup_theme', 'vecutus_setup');

// Enqueue styles and scripts
function vecutus_scripts() {
    wp_enqueue_style('vecutus-style', get_stylesheet_uri(), array(), '1.0');
    wp_enqueue_script('vecutus-js', get_template_directory_uri() . '/js/vecutus.js', array('jquery'), '1.0', true);
    
    // Add responsive viewport meta tag
    wp_enqueue_script('vecutus-responsive', get_template_directory_uri() . '/js/responsive.js', array(), '1.0', true);
}
add_action('wp_enqueue_scripts', 'vecutus_scripts');

// Register widget areas
function vecutus_widgets_init() {
    register_sidebar(array(
        'name'          => __('Sidebar', 'vecutus'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here.', 'vecutus'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'vecutus_widgets_init');

// Customizer settings
function vecutus_customize_register($wp_customize) {
    // Site Identity Section
    $wp_customize->add_setting('site_slogan', array(
        'default' => 'Your Amazing Slogan Here',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('site_slogan', array(
        'label' => __('Site Slogan', 'vecutus'),
        'section' => 'title_tagline',
        'type' => 'text',
    ));
    
    // Layout Options
    $wp_customize->add_section('vecutus_layout', array(
        'title' => __('Layout Options', 'vecutus'),
        'priority' => 30,
    ));
    
    $wp_customize->add_setting('container_width', array(
        'default' => 'container',
        'sanitize_callback' => 'vecutus_sanitize_select',
    ));
    
    $wp_customize->add_control('container_width', array(
        'label' => __('Container Width', 'vecutus'),
        'section' => 'vecutus_layout',
        'type' => 'select',
        'choices' => array(
            'container' => __('Container', 'vecutus'),
            'full-width' => __('Full Width', 'vecutus'),
        ),
    ));
    

}


add_action('customize_register', 'vecutus_customize_register');

// Sanitize select options
function vecutus_sanitize_select($input, $setting) {
    $input = sanitize_key($input);
    $choices = $setting->manager->get_control($setting->id)->choices;
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

// Custom CSS for customizer options
function vecutus_custom_css() {
    $header_bg = get_theme_mod('header_bg_color', '#2c5aa0');
    $menu_bg = get_theme_mod('menu_bg_color', '#000000');
    $container_class = get_theme_mod('container_width', 'container');
    
    echo '<style type="text/css">';
    echo '.site-header { background-color: ' . esc_attr($header_bg) . '; }';
    echo '.main-navigation { background-color: ' . esc_attr($menu_bg) . '; }';
    if ($container_class === 'full-width') {
        echo '.container { max-width: 100%; }';
    }
    echo '</style>';
}
add_action('wp_head', 'vecutus_custom_css');

/**
 * Add custom meta box for recruitment details
 */
function add_recruitment_meta_box() {
    add_meta_box(
        'recruitment_details',          // Unique ID
        'Recruitment Details',          // Box title
        'display_recruitment_meta_box', // Content callback
        'post',                        // Post type (change if needed)
        'normal',                       // Context
        'high'                          // Priority
    );
}
add_action('add_meta_boxes', 'add_recruitment_meta_box');

/**
 * Display the recruitment meta box content
 */
function display_recruitment_meta_box($post) {
    // Add a nonce field for security
    wp_nonce_field('save_recruitment_details', 'recruitment_nonce');
    
    // Get existing values from the database
    $recruitment_board = get_post_meta($post->ID, 'recruitment_board', true);
    $advt_no = get_post_meta($post->ID, 'advt_no', true);
    $total_post = get_post_meta($post->ID, 'post_name', true);
    $qualification = get_post_meta($post->ID, 'qualification', true);
    $last_date = get_post_meta($post->ID, 'last_date', true);
    $status = get_post_meta($post->ID, 'status', true);
    ?>
    
    <div style="display: grid; grid-template-columns: 1fr 3fr; gap: 15px; margin-bottom: 15px;">
        <div>
            <label for="recruitment_board" style="display: block; margin-bottom: 5px; font-weight: bold;">Recruitment Board:</label>
            <input type="text" id="recruitment_board" name="recruitment_board" value="<?php echo esc_attr($recruitment_board); ?>" style="width: 100%;">
        </div>
        
        <div>
            <label for="advt_no" style="display: block; margin-bottom: 5px; font-weight: bold;">Advertisement Number:</label>
            <input type="text" id="advt_no" name="advt_no" value="<?php echo esc_attr($advt_no); ?>" style="width: 100%;">
        </div>
        
        <div>
            <label for="post_name" style="display: block; margin-bottom: 5px; font-weight: bold;">Total Posts:</label>
            <input type="text" id="post_name" name="post_name" value="<?php echo esc_attr($total_post); ?>" style="width: 100%;">
        </div>
        
        <div>
            <label for="qualification" style="display: block; margin-bottom: 5px; font-weight: bold;">Qualification:</label>
            <input type="text" id="qualification" name="qualification" value="<?php echo esc_attr($qualification); ?>" style="width: 100%;">
        </div>
        
        <div>
            <label for="last_date" style="display: block; margin-bottom: 5px; font-weight: bold;">Last Date:</label>
            <input type="date" id="last_date" name="last_date" value="<?php echo esc_attr($last_date); ?>" style="width: 100%;">
        </div>
        
        <div>
            <label for="status" style="display: block; margin-bottom: 5px; font-weight: bold;">Status:</label>
            <select id="status" name="status" style="width: 100%;">
                <option value="New" <?php selected($status, 'New'); ?>>New</option>
                <option value="Updated" <?php selected($status, 'Updated'); ?>>Updated</option>
                <option value="Extended" <?php selected($status, 'Extended'); ?>>Extended</option>
            </select>
        </div>
    </div>
    <?php
}

/**
 * Save the custom fields when the post is saved
 */
function save_recruitment_details($post_id) {
    // Check if nonce is set and valid
    if (!isset($_POST['recruitment_nonce']) || !wp_verify_nonce($_POST['recruitment_nonce'], 'save_recruitment_details')) {
        return;
    }
    
    // Check if this is an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save each field
    $fields = array('recruitment_board', 'advt_no', 'post_name', 'qualification', 'last_date', 'status');
    
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post', 'save_recruitment_details');
/**
 * Home Page Widget
 */
function theme_custom_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Home Widget Area', 'your-theme-textdomain' ),
        'id'            => 'home-widget',
        'description'   => __( 'Widgets in this area will be shown on the homepage.', 'your-theme-textdomain' ),
        'before_widget' => '<div id="%1$s" %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'theme_custom_widgets_init' );

/**
 * Add Customizer settings for the footer
 */
function mytheme_customize_register($wp_customize) {
    // Footer Section
    $wp_customize->add_section('footer_settings', array(
        'title' => __('Footer Settings', 'mytheme'),
        'priority' => 120,
    ));

    // Copyright Text Setting
    $wp_customize->add_setting('copyright_text', array(
        'default' => '© ' . date('Y') . ' ' . get_bloginfo('name') . '. All rights reserved.',
        'sanitize_callback' => 'wp_kses_post',
    ));

    $wp_customize->add_control('copyright_text', array(
        'label' => __('Copyright Text', 'mytheme'),
        'section' => 'footer_settings',
        'type' => 'textarea',
    ));

    // Copyright Text Color Setting
    $wp_customize->add_setting('copyright_text_color', array(
        'default' => '#fff',
        'sanitize_callback' => 'sanitize_hex_color',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'copyright_text_color', array(
        'label' => __('Copyright Text Color', 'mytheme'),
        'section' => 'footer_settings',
    )));
}
add_action('customize_register', 'mytheme_customize_register');

/**
 * Theme Functions
 * Includes social media customization
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Add social media links to WordPress Customizer
 */
function theme_customize_social_media($wp_customize) {
    // Add Social Media Section
    $wp_customize->add_section('theme_social_media', array(
        'title'    => __('Social Media Links', 'textdomain'),
        'priority' => 120,
    ));

    // Social media platforms with icons
    $social_platforms = array(
        'facebook'   => array('label' => 'Facebook', 'icon' => 'fa fa-facebook-f'),
        'twitter'    => array('label' => 'Twitter', 'icon' => 'fa-twitter'),
        'instagram'  => array('label' => 'Instagram', 'icon' => 'fa-instagram'),
        'linkedin'   => array('label' => 'LinkedIn', 'icon' => 'fa-linkedin-in'),
        'youtube'    => array('label' => 'YouTube', 'icon' => 'fa-youtube'),
        'pinterest'  => array('label' => 'Pinterest', 'icon' => 'fa-pinterest-p'),
        'tiktok'     => array('label' => 'TikTok', 'icon' => 'fa-tiktok'),
        'whatsapp'   => array('label' => 'WhatsApp', 'icon' => 'fa-whatsapp'),
    );

    // Add settings and controls for each platform
    foreach ($social_platforms as $platform => $data) {
        $wp_customize->add_setting($platform . '_url', array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'        => 'refresh',
        ));

        $wp_customize->add_control($platform . '_url', array(
            'label'    => sprintf(__('%s URL', 'textdomain'), $data['label']),
            'section'  => 'theme_social_media',
            'type'     => 'url',
        ));
    }
}
add_action('customize_register', 'theme_customize_social_media');

/**
 * Enqueue Font Awesome for social icons
 */
function theme_enqueue_social_icons() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0');
}
add_action('wp_enqueue_scripts', 'theme_enqueue_social_icons');


// Ancor link generator.
function salg_enqueue_scripts() {
    // Ensure jQuery is loaded
    wp_enqueue_script('jquery');

    wp_add_inline_script(
        'jquery',
        '
        document.addEventListener("DOMContentLoaded", function() {
            const headings = document.querySelectorAll("h1, h2, h3, h4");
            const usedIds = {};

            headings.forEach((heading, index) => {
                let title = heading.textContent.trim();
                let id = title ? title.toLowerCase().replace(/[^a-z0-9]+/g, "-") : "heading";

                // Ensure the ID is unique
                if (usedIds[id]) {
                    id += "-" + (index + 1);
                }
                usedIds[id] = true;

                heading.id = id;

                const anchor = document.createElement("a");
                anchor.href = "#" + id;
                anchor.className = "anchor-link";
                anchor.textContent = "";

                heading.appendChild(anchor);
            });
        });
        ',
        'after'
    );
}
add_action('wp_enqueue_scripts', 'salg_enqueue_scripts');

// Add CSS for styling the anchor links
function salg_add_anchor_link_styles() {
    echo '
    <style>
        .anchor-link {
            text-decoration: none;
            margin-left: 8px;
            color: #007BFF;
            font-size: 0.8em;
            opacity: 0;
            transition: opacity 0.2s ease-in-out;
        }

        h1:hover .anchor-link,
        h2:hover .anchor-link,
        h3:hover .anchor-link,
        h4:hover .anchor-link {
            opacity: 1;
        }

        .anchor-link:hover {
            text-decoration: underline;
        }
    </style>';
}
add_action('wp_head', 'salg_add_anchor_link_styles');



require get_template_directory() . '/inc/license_manager.php';
