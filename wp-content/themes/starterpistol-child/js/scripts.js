jQuery(document).ready(function ($) {
	var j = $;
	
	
	// Mobile Menu
    j('.hamburger').click(function() {
		j('.site-menu.site-menu--mobile').slideToggle('500');
	})
	
	j('.site-menu__close').click(function() {
		j('.site-menu.site-menu--mobile').hide('500');
	})

    j('.mobile-menu ul li.menu-item-has-children').each(function () {
        j(this).find('a:first').attr("href", 'javascript: void(0);');
        j(this).click(function () {     
			j("li.menu-item a").removeClass("is-open").find('.site-menu.site-menu--mobile').css('display', 'none');
			j('ul.sub-menu').removeClass('is-visible');
            j(this).find('ul.sub-menu').toggleClass('is-visible');
            j(this).find('a').toggleClass('is-open');           
			
        });
    });
	
	// accordions
	j('.accordion-item').each(function() {
	  var it = j(this);
	  var title = it.find('.accordion__title');
	  var content = it.find('.accordion__content');
	  content.hide();
	  j('<span></span>').prependTo(title);
	  title.click(function() {
		// Check if the clicked item is already open
		if (title.hasClass('is-open')) {
		  // If it is open, close it
		  title.removeClass('is-open');
		  content.slideUp();
		} else {
		  // If it is closed, close the currently open item (if any) and open the clicked item
		  j('.accordion-item .accordion__title.is-open').removeClass('is-open').next().slideUp();
		  title.addClass('is-open');
		  content.slideDown();
		}
	  });
	});
	
	// Adding Accordion Classes to Woocommerce Tabs
    j('.woocommerce-tabs.wc-tabs-wrapper').addClass('accordion');
    j('.tabs.wc-tabs li').addClass('accordion__title');

	// Add Scrolling Class to Body when scrolling
    j(window).scroll(function() {
      j('body').addClass('scrolling');
    });

    // Remove Scrolling Class from Body when scroll position is at the top
    j(window).scroll(function() {
      if (j(this).scrollTop() === 0) {
        j('body').removeClass('scrolling');
      	}
	});

	
	// Sticky Menu for Mobile
	j(function(){
		createSticky(jQuery(".site-header"));
	});
	function createSticky(sticky) {
		if (typeof sticky !== "undefined") {
			var	pos = sticky.offset().top,
				win = jQuery(window);
			
			win.on("scroll", function() {                          
				win.scrollTop() > pos ? sticky.addClass("is-fixed") : sticky.removeClass("is-fixed");      
			});			
		}
	}
	
	// Add Native Lazy Loading to all Iframes
	j(document).ready(function() {
		j('iframe').attr('loading', 'lazy');
	})
	
	
	// Tabbed Function	
	j('ul.tabs li').click(function(){
		var tab_id = j(this).attr('data-tab');

		// Change URL hash without reloading the page
		window.location.hash = tab_id;

		j('ul.tabs li').removeClass('current');
		j('.tab-item').removeClass('current');

		j(this).addClass('current');
		j("#"+tab_id).addClass('current');
	});

	// Check if a tab is specified in the URL hash on page load
	var hash = window.location.hash;
	if (hash) {
		j('ul.tabs li').removeClass('current');
		j('.tab-item').removeClass('current');

		j('ul.tabs li[data-tab="' + hash.substring(1) + '"]').addClass('current');
		j(hash).addClass('current');
	}
	
	// Tabbed Mobile Accordion
	j('.tabbed-content.is-mobile .tab-item').each(function() {
		var it = j(this);
		var content = it.find('.tab-content');
		it.click(function() {
		  content.slideToggle('500');
		});
	});
	
	// Remove Overlay on Video Play
	j('.youtube').click(function() {
		j(this).has('iframe').addClass('has-video');
	});
	
	// Custom Starterpistol Pop-Up
	j('#popup-sp .inner').prepend('<a href="#" class="button--close">Close</a>');

	// Attach click event to all triggers
	j(document).on('click', '#popup-sp__cta a', function(event) {
		event.preventDefault(); // Prevent default action for the link
		j('#popup-sp, #popup-sp .inner').fadeIn('1000'); // Use fadeIn for smoother effect
	});

	// Attach click event to the close button
	j(document).on('click', '#popup-sp .button--close', function(event) {
		event.preventDefault(); // Prevent default action for the link
		j('#popup-sp, #popup-sp .inner').fadeOut('1000'); // Use fadeOut for smoother effect
	});

	// Attach click event to the overlay (outside of the popup content)
	j(document).on('click', '#popup-sp', function(event) {
		if (j(event.target).is('#popup-sp')) {
			// Check if the click is on the overlay itself
			j('#popup-sp, #popup-sp .inner').fadeOut('1000'); // Use fadeOut for smoother effect
		}
	});
	
	// Initialize Glide.js for the desktop carousel
    var glideDesktop = new Glide('#carousel-hero-desktop', {
        type: 'carousel',
        autoplay: 5000, // Adjust as needed
		animationDuration: 800,
		navigation: false // Enable navigation arrows
    });
    glideDesktop.mount();

    // Initialize Glide.js for the mobile carousel
    var glideMobile = new Glide('#carousel-hero-mobile', {
        type: 'carousel',
        autoplay: 5000, // Adjust as needed
		animationDuration: 800,
		navigation: false // Enable navigation arrows
    });
    glideMobile.mount();

});