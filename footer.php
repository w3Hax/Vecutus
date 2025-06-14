<div class="<?php echo esc_attr(get_theme_mod('container_width', 'container')); ?>">
    <footer id="colophon" class="site-footer">
        <div class="footer-content" style="margin: <?php echo esc_attr(get_theme_mod('footer_margin', '10px 50px')); ?>;">
			<div class="copyright" style="color: <?php echo esc_attr(get_theme_mod('copyright_text_color', '#fff')); ?>; text-align: <?php echo esc_attr(get_theme_mod('copyright_text_align', 'center')); ?>;">
                <p><?php echo wp_kses_post(get_theme_mod('copyright_text', '© ' . date('Y') . ' ' . get_bloginfo('name') . '. All rights reserved.')); ?></p>
            </div>
			<!-- Social Media Links -->
        <div class="social-media-wrapper">
            <?php
            $social_platforms = array(
                'facebook'   => array('label' => 'Facebook', 'icon' => 'fa fa-facebook-f'),
                'twitter'    => array('label' => 'Twitter', 'icon' => 'fa fa-twitter'),
                'instagram'  => array('label' => 'Instagram', 'icon' => 'fa fa-instagram'),
                'linkedin'   => array('label' => 'LinkedIn', 'icon' => 'fa fa-linkedin-in'),
                'youtube'    => array('label' => 'YouTube', 'icon' => 'fa fa-youtube'),
                'pinterest'  => array('label' => 'Pinterest', 'icon' => 'fa fa-pinterest-p'),
                'tiktok'     => array('label' => 'TikTok', 'icon' => 'fa fa-tiktok'),
                'whatsapp'   => array('label' => 'WhatsApp', 'icon' => 'fa fa-whatsapp'),
            );

            foreach ($social_platforms as $platform => $data) {
                $url = get_theme_mod($platform . '_url');
                if (!empty($url)) {
                    echo sprintf(
                        '<a href="%s" target="_blank" rel="noopener noreferrer" class="social-link %s" aria-label="%s">',
                        esc_url($url),
                        esc_attr($platform),
                        esc_attr($data['label'])
                    );
                    echo '<i class="fab ' . esc_attr($data['icon']) . '"></i>';
                    echo '</a>';
                }
            }
            ?>
        </div>
            <div class="footer-menu">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'menu_id' => 'footer-menu',
                    'menu_class' => 'footer-nav',
                    'fallback_cb' => false,
                ));
                ?>
            </div>
            
            
        </div>
    </footer>
</div>

<?php wp_footer(); ?>
</body>
</html>