<?php 
add_filter('pre_set_site_transient_update_themes', 'github_theme_updates_check');

function github_theme_updates_check($transient) {
    if (empty($transient->checked)) {
        return $transient;
    }
    
    $theme_data = wp_get_theme();
    $theme_slug = $theme_data->get_template();
    
    $github_username = 'w3hax';
    $github_repo = 'vecutus';
    
    $github_response = wp_remote_get(
        sprintf('https://api.github.com/repos/%s/%s/releases/latest', $github_username, $github_repo),
        array('headers' => array('Accept' => 'application/vnd.github.v3+json'))
    );
    
    if (!is_wp_error($github_response)) {
        $github_data = json_decode($github_response['body']);
        
        if (version_compare($theme_data->get('Version'), $github_data->tag_name, '<')) {
            $transient->response[$theme_slug] = array(
                'theme'       => $theme_slug,
                'new_version' => $github_data->tag_name,
                'url'         => $github_data->html_url,
                'package'     => $github_data->zipball_url
            );
        }
    }
    
    return $transient;
}