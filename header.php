<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
	<div class="<?php echo esc_attr(get_theme_mod('container_width', 'container')); ?>">
    <header id="masthead" class="site-header">
        
            <div class="header-content cont-padding">
                <div class="site-branding">
                    <?php if (has_custom_logo()) : ?>
                        <div class="site-logo">
                            <?php the_custom_logo(); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="site-info">
                        <h2 class="site-title">
                            <a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
                        </h2>
                        <p class="site-description"><?php echo esc_html(get_theme_mod('site_tagline', get_bloginfo('description'))); ?></p>
                    </div>
                </div>
                
            </div>
        
    </header>

    <nav id="site-navigation" class="main-navigation cont-padding">
        
            <div class="nav-container">
                <button class="menu-toggle" id="mobile-menu-toggle">
                    <span class="hamburger"></span>
                    <span class="hamburger"></span>
                    <span class="hamburger"></span>
                </button>
                
                <div class="nav-menu-wrapper" id="nav-menu-wrapper">
                    <button class="close-menu" id="close-menu">✕</button>
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_id' => 'primary-menu',
                        'menu_class' => 'nav-menu',
                        'fallback_cb' => false,
                    ));
                    ?>
                </div>
				<div class="header-right">
                    <div class="search-toggle">
                        <button id="search-toggle" class="search-toggle-btn">
                            <span class="search-icon">🔍</span>
                        </button>
                        <div id="search-form" class="search-form-container">
                            <?php get_search_form(); ?>
                        </div>
                    </div>
                </div>
                
            
            </div>
		
        
    </nav>

		<div class="secondary-menu-container">
                    <div class="secondary-menu-scroll" id="secondary-menu">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'secondary',
                            'menu_id' => 'secondary-menu',
                            'menu_class' => 'secondary-nav',
                            'fallback_cb' => false,
                        ));
                        ?>
                    </div>
                </div>
	</div>
	