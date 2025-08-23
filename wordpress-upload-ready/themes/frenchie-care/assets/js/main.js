/**
 * Frenchie Care Theme JavaScript
 */

(function($) {
    'use strict';
    
    // Mobile menu toggle
    $('.menu-toggle').on('click', function() {
        $(this).toggleClass('active');
        $('.main-navigation ul').slideToggle();
    });
    
    // Smooth scroll for anchor links
    $('a[href*="#"]').not('[href="#"]').not('[href="#0"]').on('click', function(event) {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                event.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 1000);
            }
        }
    });
    
    // Track post views
    if ($('body').hasClass('single-post')) {
        var postId = $('article').attr('id').replace('post-', '');
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'track_post_views',
                post_id: postId
            }
        });
    }
    
    // Lead capture form submission
    $('.lead-capture-form').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        var email = $form.find('input[type="email"]').val();
        
        // Basic email validation
        if (!isValidEmail(email)) {
            alert('Please enter a valid email address.');
            return;
        }
        
        // Here you would typically send to your email service
        // For now, just show success message
        $form.html('<p style="color: #48BB78;">Thank you! Check your email for your free guide.</p>');
        
        // Store in localStorage to prevent repeat popups
        localStorage.setItem('frenchie_lead_captured', 'true');
    });
    
    // Exit intent popup for lead capture
    var exitIntentShown = false;
    $(document).on('mouseleave', function(e) {
        if (e.clientY < 10 && !exitIntentShown && !localStorage.getItem('frenchie_lead_captured')) {
            exitIntentShown = true;
            showExitIntentPopup();
        }
    });
    
    // Sticky sidebar
    if ($(window).width() > 768) {
        var $sidebar = $('.sidebar');
        var $content = $('.content-area');
        
        $(window).on('scroll', function() {
            var scrollTop = $(window).scrollTop();
            var contentHeight = $content.height();
            var sidebarHeight = $sidebar.height();
            var windowHeight = $(window).height();
            
            if (contentHeight > sidebarHeight) {
                if (scrollTop > 100) {
                    $sidebar.css({
                        'position': 'fixed',
                        'top': '20px',
                        'width': $sidebar.parent().width()
                    });
                } else {
                    $sidebar.css({
                        'position': 'static',
                        'width': 'auto'
                    });
                }
            }
        });
    }
    
    // Helper functions
    function isValidEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }
    
    function showExitIntentPopup() {
        var popupHtml = '<div class="exit-intent-popup">' +
            '<div class="popup-content">' +
            '<span class="close-popup">&times;</span>' +
            '<h2>Wait! Don\'t Leave Empty-Handed</h2>' +
            '<p>Get our FREE Frenchie Allergy Quick-Start Guide!</p>' +
            '<form class="lead-capture-form">' +
            '<input type="email" placeholder="Your email address" required>' +
            '<button type="submit">Send Me The Guide</button>' +
            '</form>' +
            '</div>' +
            '</div>';
        
        $('body').append(popupHtml);
        
        $('.close-popup').on('click', function() {
            $('.exit-intent-popup').remove();
        });
        
        $('.exit-intent-popup').on('click', function(e) {
            if (e.target === this) {
                $(this).remove();
            }
        });
    }
    
    // Lazy load images
    if ('IntersectionObserver' in window) {
        var imageObserver = new IntersectionObserver(function(entries, observer) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    var image = entry.target;
                    image.src = image.dataset.src;
                    image.classList.remove('lazy');
                    imageObserver.unobserve(image);
                }
            });
        });
        
        var lazyImages = document.querySelectorAll('img.lazy');
        lazyImages.forEach(function(image) {
            imageObserver.observe(image);
        });
    }
    
    // Table of contents generation for long posts
    if ($('.entry-content').length && $('.entry-content h2').length > 3) {
        var toc = '<div class="table-of-contents"><h3>Table of Contents</h3><ul>';
        var tocItems = [];
        
        $('.entry-content h2').each(function(index) {
            var $heading = $(this);
            var id = 'toc-' + index;
            $heading.attr('id', id);
            tocItems.push('<li><a href="#' + id + '">' + $heading.text() + '</a></li>');
        });
        
        toc += tocItems.join('') + '</ul></div>';
        $('.entry-content').prepend(toc);
    }
    
    // Print-friendly version
    $('.print-article').on('click', function(e) {
        e.preventDefault();
        window.print();
    });
    
})(jQuery);