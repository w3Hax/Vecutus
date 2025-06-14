jQuery(document).ready(function($) {
    // Mobile menu toggle
    $('#mobile-menu-toggle').on('click', function() {
        $('#nav-menu-wrapper').addClass('active');
        $('body').addClass('menu-open');
    });
    
    $('#close-menu').on('click', function() {
        $('#nav-menu-wrapper').removeClass('active');
        $('body').removeClass('menu-open');
    });
    
    // Close menu when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#nav-menu-wrapper, #mobile-menu-toggle').length) {
            $('#nav-menu-wrapper').removeClass('active');
            $('body').removeClass('menu-open');
        }
    });
    
    // Search toggle
    $('#search-toggle').on('click', function(e) {
        e.stopPropagation();
        $('#search-form').toggleClass('active');
    });
    
    // Close search when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.search-toggle').length) {
            $('#search-form').removeClass('active');
        }
    });
    
    // Secondary menu scroll on mobile
    function initSecondaryMenuScroll() {
        const secondaryMenu = $('#secondary-menu');
        if (secondaryMenu.length && $(window).width() <= 768) {
            let isScrolling = false;
            let startX, scrollLeft;
            
            secondaryMenu.on('touchstart', function(e) {
                isScrolling = true;
                startX = e.touches[0].pageX - secondaryMenu.offset().left;
                scrollLeft = secondaryMenu.scrollLeft();
            });
            
            secondaryMenu.on('touchmove', function(e) {
                if (!isScrolling) return;
                e.preventDefault();
                const x = e.touches[0].pageX - secondaryMenu.offset().left;
                const walk = (x - startX) * 2;
                secondaryMenu.scrollLeft(scrollLeft - walk);
            });
            
            secondaryMenu.on('touchend', function() {
                isScrolling = false;
            });
        }
    }
    
    // Initialize secondary menu scroll
    initSecondaryMenuScroll();
    
    // Reinitialize on window resize
    $(window).on('resize', function() {
        initSecondaryMenuScroll();
    });
    
    // Smooth scroll for anchor links
    $('a[href*="#"]:not([href="#"])').on('click', function() {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 1000);
                return false;
            }
        }
    });
    
    // Add loading animation to forms
    $('form').on('submit', function() {
        $(this).find('input[type="submit"], button[type="submit"]').prop('disabled', true).text('Loading...');
    });
    
    // Back to top button
    if ($('#back-to-top').length === 0) {
        $('body').append('<button id="back-to-top" style="position: fixed; bottom: 20px; right: 20px; color: white; border: none; padding: 13px 20px; border-radius: 50%; cursor: pointer; display: none; z-index: 1000; font-size: 18px;">↑</button>');
    }
    
    $(window).on('scroll', function() {
        if ($(this).scrollTop() > 300) {
            $('#back-to-top').fadeIn();
        } else {
            $('#back-to-top').fadeOut();
        }
    });
    
    $('#back-to-top').on('click', function() {
        $('html, body').animate({scrollTop: 0}, 800);
    });
});
