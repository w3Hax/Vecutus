<?php
// Add Theme Customizer Color Options
function theme_color_customizer($wp_customize) {
    // Add Color Options Section
    $wp_customize->add_section('theme_colors', array(
        'title'    => __('Theme Colors', 'your-theme-textdomain'),
        'priority' => 30,
    ));

    // Body Background Color
    $wp_customize->add_setting('body_bg_color', array(
        'default'           => '#d5d8dc',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'        => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'body_bg_color', array(
        'label'    => __('Body Background Color', 'your-theme-textdomain'),
        'section'  => 'theme_colors',
        'settings' => 'body_bg_color',
    )));

    // Header Background Color
    $wp_customize->add_setting('header_bg_color', array(
        'default'           => '#b21d1d',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'        => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'header_bg_color', array(
        'label'    => __('Header Background Color', 'your-theme-textdomain'),
        'section'  => 'theme_colors',
        'settings' => 'header_bg_color',
    )));

    // Navigation Background Color
    $wp_customize->add_setting('nav_bg_color', array(
        'default'           => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'        => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'nav_bg_color', array(
        'label'    => __('Navigation Background Color', 'your-theme-textdomain'),
        'section'  => 'theme_colors',
        'settings' => 'nav_bg_color',
    )));

    // Secondary Navigation Background Color
    $wp_customize->add_setting('secondary_nav_bg_color', array(
        'default'           => '#444444',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'        => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'secondary_nav_bg_color', array(
        'label'    => __('Secondary Navigation Background Color', 'your-theme-textdomain'),
        'section'  => 'theme_colors',
        'settings' => 'secondary_nav_bg_color',
    )));

    // Link Color
    $wp_customize->add_setting('link_color', array(
        'default'           => '#0066cc',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'        => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'link_color', array(
        'label'    => __('Link Color', 'your-theme-textdomain'),
        'section'  => 'theme_colors',
        'settings' => 'link_color',
    )));

    // Post Box Title Background Color
    $wp_customize->add_setting('post_title_bg_color', array(
        'default'           => '#1e73be',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'        => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'post_title_bg_color', array(
        'label'    => __('Post Box Title Background Color', 'your-theme-textdomain'),
        'section'  => 'theme_colors',
        'settings' => 'post_title_bg_color',
    )));

    // View More Button Background Color
    $wp_customize->add_setting('view_more_button_bg_color', array(
        'default'           => '#007bff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'        => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'view_more_button_bg_color', array(
        'label'    => __('View More Button Background Color', 'your-theme-textdomain'),
        'section'  => 'theme_colors',
        'settings' => 'view_more_button_bg_color',
    )));

    // Apply Now Button Background Color
    $wp_customize->add_setting('apply_now_button_bg_color', array(
        'default'           => '#28a745',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'        => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'apply_now_button_bg_color', array(
        'label'    => __('Apply Now Button Background Color', 'your-theme-textdomain'),
        'section'  => 'theme_colors',
        'settings' => 'apply_now_button_bg_color',
    )));

    // Footer Background Color
    $wp_customize->add_setting('footer_bg_color', array(
        'default'           => '#343a40',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'        => 'postMessage',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'footer_bg_color', array(
        'label'    => __('Footer Background Color', 'your-theme-textdomain'),
        'section'  => 'theme_colors',
        'settings' => 'footer_bg_color',
    )));
}
add_action('customize_register', 'theme_color_customizer');

// Output Custom Colors in Frontend
function theme_customizer_css() {
    ?>
    <style type="text/css">
        /* Body Background */
        body, .secondary-menu-scroll {
            background-color: <?php echo esc_attr(get_theme_mod('body_bg_color', '#d5d8dc')); ?>;
        }

        /* Header Background */
        .site-header, #back-to-top {
            background-color: <?php echo esc_attr(get_theme_mod('header_bg_color', '#b21d1d')); ?>;
        }
        #site-navigation ul li:hover>a, #site-navigation ul li.current-menu-item>a, #site-navigation ul li.current_page_item>a, #site-navigation ul li.current-menu-ancestor>a, #site-navigation ul li.focus>a {
            background-color: <?php echo esc_attr(get_theme_mod('header_bg_color', '#ffffff')); ?>;
            opacity: <?php echo esc_attr(get_theme_mod('header_bg_opacity', '2')); ?>;
        } 

        /* Navigation Background */
        .main-navigation {
            background-color: <?php echo esc_attr(get_theme_mod('nav_bg_color', '#333333')); ?>;
        }

        /* Secondary Navigation Background */
        .secondary-nav a {
            background-color: <?php echo esc_attr(get_theme_mod('secondary_nav_bg_color', '#444444')); ?>;
        }

        /* Link Color */
        .vecutus-marquee a, .jbp-post p a {
            color: <?php echo esc_attr(get_theme_mod('link_color', '#0066cc')); ?>;
        }

        /* Post Box Title Background */
        .jbp-column h2, .widget-title,table tr th {
            background-color: <?php echo esc_attr(get_theme_mod('post_title_bg_color', '#1e73be')); ?>;
        }

        /* View More Button Background */
        .jbp-archive-button, .pagination a:hover, .pagination .current {
            background-color: <?php echo esc_attr(get_theme_mod('view_more_button_bg_color', '#007bff')); ?>;
        }

        /* Apply Now Button Background */
        .jbp-apply-button {
            background-color: <?php echo esc_attr(get_theme_mod('apply_now_button_bg_color', '#28a745')); ?>;
        }

        /* Footer Background */
         .site-footer {
            background-color: <?php echo esc_attr(get_theme_mod('footer_bg_color', '#343a40')); ?>;
        }
    </style>
    <?php
}
add_action('wp_head', 'theme_customizer_css');

// Enqueue Customizer Live Preview Script
function theme_customizer_live_preview() {
    wp_add_inline_script('customize-preview', '
        (function($) {
            // Body Background Color
            wp.customize("body_bg_color", function(value) {
                value.bind(function(newval) {
                    $("body, .secondary-menu-scroll").css("background-color", newval);
                });
            });

            // Header Background Color
            wp.customize("header_bg_color", function(value) {
                value.bind(function(newval) {
                    $("header, .site-header").css("background-color", newval);
                });
            });

            // Navigation Background Color
            wp.customize("nav_bg_color", function(value) {
                value.bind(function(newval) {
                    $("nav, .main-navigation").css("background-color", newval);
                });
            });

            // Secondary Navigation Background Color
            wp.customize("secondary_nav_bg_color", function(value) {
                value.bind(function(newval) {
                    $(".secondary-nav a").css("background-color", newval);
                });
            });

            // Link Color
            wp.customize("link_color", function(value) {
                value.bind(function(newval) {
                    $(".vecutus-marquee a, .jbp-post p a").css("color", newval);
                });
            });

            // Post Box Title Background Color
            wp.customize("post_title_bg_color", function(value) {
                value.bind(function(newval) {
                    $(".jbp-column h2, .widget-title, table tr th").css("background-color", newval);
                });
            });

            // View More Button Background Color
            wp.customize("view_more_button_bg_color", function(value) {
                value.bind(function(newval) {
                    $(".jbp-archive-button, .pagination a:hover, .pagination .current").css("background-color", newval);
                });
            });

            // Apply Now Button Background Color
            wp.customize("apply_now_button_bg_color", function(value) {
                value.bind(function(newval) {
                    $(".jbp-apply-button").css("background-color", newval);
                });
            });

            // Footer Background Color
            wp.customize("footer_bg_color", function(value) {
                value.bind(function(newval) {
                    $(".site-footer").css("background-color", newval);
                });
            });
        })(jQuery);
    ');
}
add_action('customize_preview_init', 'theme_customizer_live_preview');
?>