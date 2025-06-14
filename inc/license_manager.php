<?php
/*
Plugin Name: W3T License Manager
*/

if (!defined('ABSPATH')) exit;

class W3T_License_Buyer {
    private $api_url = 'https://www.w3templates.com/wp-json/w3t-license/v1/validate';
    private $option_name = 'w3t_license_data';
    private $buy_license_url = 'https://www.w3templates.com/pricing/';

    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_page']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_notices', [$this, 'show_license_notice']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_styles']);
        add_action('customize_controls_print_scripts', [$this, 'add_customizer_license_status']);
        add_action('admin_bar_menu', [$this, 'add_admin_bar_license_status'], 999);
        
        // Check license status and include theme files if active
        add_action('after_setup_theme', [$this, 'include_theme_files']);
    }

 public function include_theme_files() {
        $license_data = get_option($this->option_name, ['license_key' => '', 'status' => 'inactive']);
        
        if ($license_data['status'] === 'active') {
            $files = [
                '/inc/widgets/post_box.php',
                '/inc/widgets/marquee.php',
                '/inc/widgets/color_box.php',
                '/inc/widgets/social_button.php',
                '/inc/customizer/color.php',
				'/inc/updator.php'
            ];
            foreach ($files as $file) {
                $path = get_template_directory() . $file;
                if (file_exists($path)) {
                    require_once $path;
                }
            }
        }
    }

    public function add_admin_bar_license_status($wp_admin_bar) {
        $license_data = get_option($this->option_name, ['license_key' => '', 'status' => 'inactive']);
        
        if ($license_data['status'] !== 'active') {
            $wp_admin_bar->add_node([
                'id'    => 'w3t-license-status',
                'title' => '<span class="ab-icon dashicons dashicons-warning" style="color:#dc3232;"></span> ' . __('Theme License Inactive', 'w3t'),
                'href'  => admin_url('admin.php?page=w3t-license-buyer'),
                'meta'  => [
                    'class' => 'w3t-license-inactive',
                    'title' => __('Activate your theme license', 'w3t')
                ]
            ]);
            
            // Add "Buy License" button next to the theme name
            $wp_admin_bar->add_node([
                'id'    => 'w3t-buy-license',
                'title' => __('Buy License', 'w3t'),
                'href'  => $this->buy_license_url,
                'parent' => 'site-name',
                'meta'  => [
                    'class' => 'w3t-buy-license',
                    'title' => __('Purchase a license for this theme', 'w3t'),
                    'target' => '_blank'
                ]
            ]);
        }
    }

    public function add_customizer_license_status() {
        $license_data = get_option($this->option_name, ['license_key' => '', 'status' => 'inactive']);
        ?>
        <script>
            jQuery(document).ready(function($) {
                // Add license status to customizer header
                var licenseStatus = $('<div class="w3t-license-status-customizer"></div>');
                
                <?php if ($license_data['status'] === 'active'): ?>
                    licenseStatus.html(
                        '<span class="dashicons dashicons-yes" style="color:#46b450;"></span> ' +
                        '<span style="color:#46b450;"><?php _e("Theme License: Active", "w3t"); ?></span>'
                    );
                <?php else: ?>
                    licenseStatus.html(
                        '<span class="dashicons dashicons-no" style="color:#dc3232;"></span> ' +
                        '<span style="color:#dc3232;"><?php _e("Theme License: Inactive", "w3t"); ?></span>'
                    );
                <?php endif; ?>
                
                // Insert at the beginning of customizer header
                $('.wp-full-overlay-header').prepend(licenseStatus);
                
                // Add minimal styling
                $('<style>')
                    .text('.w3t-license-status-customizer { padding: 10px 15px; font-size: 13px; font-weight: 600; }')
                    .appendTo('head');
            });
        </script>
        <?php
    }

    public function enqueue_styles($hook) {
        if ($hook !== 'toplevel_page_w3t-license-buyer') {
            return;
        }
        wp_enqueue_style('w3t-tailwind', 'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css');
    }

    public function add_admin_page() {
        add_menu_page(
            'Theme License',
            'Theme License',
            'manage_options',
            'w3t-license-buyer',
            [$this, 'license_page'],
            'dashicons-admin-network',
            81
        );
    }

    public function register_settings() {
        register_setting('w3t_license_settings', $this->option_name, [
            'sanitize_callback' => [$this, 'sanitize_license_data']
        ]);
    }

    public function sanitize_license_data($input) {
        $new_input = [];
        if (isset($input['license_key'])) {
            $new_input['license_key'] = sanitize_text_field($input['license_key']);
        }
        if (isset($input['status'])) {
            $new_input['status'] = in_array($input['status'], ['active', 'inactive']) ? $input['status'] : 'inactive';
        }
        return $new_input;
    }

    public function license_page() {
        $license_data = get_option($this->option_name, ['license_key' => '', 'status' => 'inactive']);
        $status_class = $license_data['status'] === 'active' ? 'bg-green-100 border-green-500 text-green-700' : 'bg-red-100 border-red-500 text-red-700';
        $status_icon = $license_data['status'] === 'active' ? '✅' : '❌';
        $is_active = $license_data['status'] === 'active';

        if (isset($_POST['w3t_submit_license'])) {
            check_admin_referer('w3t_license_verify');
            $license_key = sanitize_text_field($_POST['license_key']);
            $response = $this->validate_license($license_key);
            
            if (is_wp_error($response)) {
                echo '<div class="notice notice-error is-dismissible"><p>Error: ' . esc_html($response->get_error_message()) . '</p></div>';
            } else {
                $body = json_decode(wp_remote_retrieve_body($response), true);
                
                if ($body['success']) {
                    $license_data = [
                        'license_key' => $license_key,
                        'status' => 'active',
                        'domain' => home_url(),
                        'activated_at' => $body['data']['activated_at']
                    ];
                    update_option($this->option_name, $license_data);
                    echo '<div class="notice notice-success is-dismissible"><p>License activated successfully!</p></div>';
                    $status_class = 'bg-green-100 border-green-500 text-green-700';
                    $status_icon = '✅';
                    $is_active = true;
                } else {
                    $license_data['status'] = 'inactive';
                    update_option($this->option_name, $license_data);
                    echo '<div class="notice notice-error is-dismissible"><p>' . esc_html($body['message']) . '</p></div>';
                    $status_class = 'bg-red-100 border-red-500 text-red-700';
                    $status_icon = '❌';
                    $is_active = false;
                }
            }
        }

        if (isset($_POST['w3t_deactivate_license'])) {
            check_admin_referer('w3t_license_verify');
            $license_data = [
                'license_key' => $license_data['license_key'],
                'status' => 'inactive',
                'domain' => '',
                'activated_at' => ''
            ];
            update_option($this->option_name, $license_data);
            echo '<div class="notice notice-success is-dismissible"><p>License deactivated successfully!</p></div>';
            $status_class = 'bg-red-100 border-red-500 text-red-700';
            $status_icon = '❌';
            $is_active = false;
        }

        ?>
        <div class="wrap">
            <div class="max-w-2xl mx-auto mt-8 p-6 bg-white rounded-lg shadow-xl">
                <h1 class="text-3xl font-bold text-gray-800 mb-6">W3Templates Theme License</h1>
                
                <div class="mb-6 p-4 border-l-4 <?php echo esc_attr($status_class); ?> rounded-r-lg">
                    <div class="flex items-center">
                        <span class="text-2xl mr-2"><?php echo $status_icon; ?></span>
                        <div>
                            <p class="font-semibold">License Status: 
                                <span class="capitalize"><?php echo esc_html($license_data['status']); ?></span>
                            </p>
                            <?php if ($license_data['status'] === 'active' && isset($license_data['activated_at'])): ?>
                                <p class="text-sm mt-1">Activated on: <?php echo esc_html($license_data['activated_at']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <form method="post" action="" class="space-y-6">
                    <?php wp_nonce_field('w3t_license_verify'); ?>
                    <div>
                        <label for="license_key" class="block text-sm font-medium text-gray-700 mb-2">
                            License Key
                        </label>
                        <input type="text" 
                               name="license_key" 
                               id="license_key" 
                               value="<?php echo esc_attr($license_data['license_key']); ?>" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 <?php echo $is_active ? 'bg-gray-100 cursor-not-allowed' : ''; ?>" 
                               placeholder="W3T-XXXX-XXXX-XXXX" 
                               <?php echo $is_active ? 'readonly' : 'required'; ?>>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <?php if ($is_active): ?>
                            <?php submit_button(
                                'Deactivate License', 
                                'inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500', 
                                'w3t_deactivate_license'
                            ); ?>
                        <?php else: ?>
                            <?php submit_button(
                                'Activate License', 
                                'primary inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500', 
                                'w3t_submit_license'
                            ); ?>
                            <a href="<?php echo esc_url($this->buy_license_url); ?>" target="_blank" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                Buy License
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }

    private function validate_license($license_key) {
        $domain = home_url();
        $args = [
            'method' => 'POST',
            'timeout' => 30,
            'body' => wp_json_encode([
                'license_key' => $license_key,
                'domain' => $domain
            ]),
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ];

        return wp_remote_request($this->api_url, $args);
    }

    public function show_license_notice() {
        $license_data = get_option($this->option_name, ['license_key' => '', 'status' => 'inactive']);
        
        if ($license_data['status'] !== 'active') {
            ?>
            <div class="notice notice-warning is-dismissible">
                <p>Please activate your W3Templates theme license to receive updates and support. 
                   <a href="<?php echo admin_url('admin.php?page=w3t-license-buyer'); ?>" class="underline text-blue-600 hover:text-blue-800">Activate Now</a> or 
                   <a href="<?php echo esc_url($this->buy_license_url); ?>" target="_blank" class="underline text-purple-600 hover:text-purple-800">Buy License</a></p>
            </div>
            <?php
        }
    }
}

new W3T_License_Buyer();