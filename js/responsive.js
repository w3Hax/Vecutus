// Responsive utilities
(function() {
    'use strict';
    
    // Add viewport meta tag if not present
    if (!document.querySelector('meta[name="viewport"]')) {
        var viewport = document.createElement('meta');
        viewport.name = 'viewport';
        viewport.content = 'width=device-width, initial-scale=1';
        document.getElementsByTagName('head')[0].appendChild(viewport);
    }
    
    // Handle orientation change
    window.addEventListener('orientationchange', function() {
        setTimeout(function() {
            window.scrollTo(0, 1);
        }, 500);
    });
    
    // Prevent zoom on input focus on iOS
    if (/iPad|iPhone|iPod/.test(navigator.userAgent)) {
        var inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="search"], textarea');
        inputs.forEach(function(input) {
            input.addEventListener('focus', function() {
                input.style.fontSize = '16px';
            });
            input.addEventListener('blur', function() {
                input.style.fontSize = '';
            });
        });
    }
    
    // Handle window resize
    var resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            // Trigger custom resize event
            window.dispatchEvent(new Event('optimizedResize'));
        }, 250);
    });
})();