/**
 * EVE Online Theme Main JavaScript
 *
 * Author: Rounon Dax (Terra Nanotech / Yulai Federation)
 */

/* Stuff without jQuery
----------------------------------------------------------------------------- */
/**
 * Detecting JS Support
 *
 * @param {type} body
 * @returns {undefined}
 */
(function(body) {
	body.className = body.className.replace(/\bno-js\b/,'js')
})(document.body);

/**
 * Detecting mobile devices
 *
 * @returns {boolean} true/false
 */
function isMobile() {
	return navigator.userAgent.match(/(iPhone|iPod|iPad|blackberry|android|Kindle|htc|lg|midp|mmp|mobile|nokia|opera mini|palm|pocket|psp|sgh|smartphone|symbian|treo mini|Playstation Portable|SonyEricsson|Samsung|MobileExplorer|PalmSource|Benq|Windows Phone|Windows Mobile|IEMobile|Windows CE|Nintendo Wii)/i);
} // END function isMobile()

/* Stuff that needs jQuery
----------------------------------------------------------------------------- */
jQuery(function($) {
	// Detecting jQuery Support
	$('body').addClass('jquery');

	/**
	 * Lets make the slider stretch, if it should be.
	 */
	$('body .meta-slider[data-stretch="true"]').each(function() {
		/**
		 * @type object
		 */
		var $$ = $(this);

		$$.find('>div').css('max-width', '100%');
	});

	/**
	 * fix for automatically generated menues to work with Bootstrap
	 *
	 * @param {string} device
	 * @returns {undefined}
	 */
	function fixAutomaticMenu(device) {
		var $autMenuItems = $('ul.nav > li.page_item_has_children');

		/**
		 * just in case we have the original bootstrap menu ...
		 */
		if($autMenuItems.length === 0) {
			$autMenuItems = $('ul.nav > li.menu-item-has-children')
		} // END if($autMenuItems.length === 0)

		// fixing the menu for desktop
		$autMenuItems.each(function() {
			if(device === 'desktop') {
				$(this).parent('ul.nav').removeClass('mobile-nav');
				$(this).parent('ul.nav').addClass('desktop-nav');

				$(this).addClass('dropdown');

				// Remove wrongly set .caret spans
				$(this).find('>span.caret').remove();
				$(this).find('>a span.caret').remove();

				$(this).find('li.current_page_item').addClass('active');

				$(this).find('>a').addClass('dropdown-toggle');
				$(this).find('>a').attr('data-toggle', 'dropdown');

				// Set the .caret span again at the right position
				$(this).find('>a').append('<span class="caret"></span>');

				// remove the parents link on mobile devices
				if(isMobile()) {
					$(this).find('>a').attr('href', '#');
				} // END if(isMobile())

				// 1st Level
				$(this).find('>ul.children').addClass('dropdown-menu multi-level');

				// 2nd Level and maybe more?
				$(this).find('>ul.children li.page_item_has_children').addClass('dropdown-submenu')
				$(this).find('>ul.children ul').addClass('dropdown-menu');
			} // END if(device === 'desktop')

			// fixing stuff for mobile
			if(device === 'mobile') {
				$(this).parent('ul.nav').addClass('mobile-nav');
				$(this).parent('ul.nav').removeClass('desktop-nav');

				$(this).addClass('dropdown');

				// Remove wrongly set .caret spans
				$(this).find('>a > span.caret').remove();
				$(this).find('>span.caret').remove();

				$(this).find('li.current_page_item').addClass('active');

				$(this).find('>a').removeClass('dropdown-toggle');
				$(this).find('>a').attr('data-toggle', '');

				// Set the .caret span again at the right position
				$(this).find('>a').after('<span class="caret"><i></i></span>');
				$(this).find('>span.caret').addClass('dropdown-toggle');
				$(this).find('>span.caret').attr('data-toggle', 'dropdown');

				// 1st Level
				$(this).find('>ul.children').addClass('dropdown-menu multi-level');

				// 2nd Level and maybe more?
				$(this).find('>ul.children li.page_item_has_children').addClass('dropdown-submenu')
				$(this).find('>ul.children ul').addClass('dropdown-menu');
			} // END if(device == 'mobile')
		});
	} // END function fixAutomaticMenu(viewport)

	(function($, viewport) {
		// the initial viewport (on page load)
		if(viewport.is('xs')) {
			fixAutomaticMenu('mobile');
        } else {
			fixAutomaticMenu('desktop');
		} // END if(viewport.is('xs'))

		// if the browser gets resized
		$(window).resize(
			viewport.changed(function() {
				if(viewport.is('xs')) {
					fixAutomaticMenu('mobile');
				} else {
					fixAutomaticMenu('desktop');
				} // END if(viewport.is('xs'))
			}, 1)
	   );
	})(jQuery, ResponsiveBootstrapToolkit);

	// make parent clickable if not on mobile device
	if(!isMobile()) {
		$('.navbar .dropdown > a').click(function() {
			location.href = this.href;
		});
	} // END if(!isMobile())

	/**
	 * Fix for youtube and vimeo oEmbed being responsive
	 */
	var $oEmbedVideos = $('iframe[src*="youtube"], iframe[src*="vimeo"]');
	$oEmbedVideos.each(function() {
		$(this).removeAttr('height').removeAttr('width').wrap('<div class="embed-video-container"></div>');
	});

	// Removing the obsolete frameborder attribute to be HTML5 compliant
	var $iFrames = $('iframe');
	$iFrames.each(function() {
		$(this).removeAttr('frameborder');
	});

	/**
	 * Set bootstrap modal window to max possible height
	 *
	 * @param {string} element
	 * @returns {eve-online_L21.setModalMaxHeight}
	 */
	function setModalMaxHeight(element) {
		this.$element = $(element);
		this.$content = this.$element.find('.modal-content');

		/**
		 * @type int
		 */
		var borderWidth = this.$content.outerHeight() - this.$content.innerHeight();

		/**
		 * @type Number
		 */
		var dialogMargin = $(window).width() < 768 ? 20 : 60;

		/**
		 * @type setModalMaxHeight@pro;$content@call;innerHeight|setModalMaxHeight@pro;$content@call;outerHeight|int|Number
		 */
		var contentHeight = $(window).height() - (dialogMargin + borderWidth);

		/**
		 * @type Number
		 */
		var headerHeight = this.$element.find('.modal-header').outerHeight() || 0;

		/**
		 * @type Number
		 */
		var footerHeight = this.$element.find('.modal-footer').outerHeight() || 0;

		/**
		 * @type Number|setModalMaxHeight@pro;$element@call;find@call;outerHeight|setModalMaxHeight@pro;$content@call;innerHeight|setModalMaxHeight@pro;$content@call;outerHeight|int
		 */
		var maxHeight = contentHeight - (headerHeight + footerHeight);

//		this.$content.css({
//			'overflow': 'hidden'
//		});

		this.$element.find('.modal-body').css({
			'max-height': maxHeight
//			'overflow-y': 'auto'
		});
	} // END function setModalMaxHeight(element)

	/**
	 * Start the modal window thingy
	 */
	$('.modal').on('show.bs.modal', function() {
		$(this).show();

		setModalMaxHeight(this);
	});

	/**
	 * on resize, restart the modal window stuff
	 */
	$(window).resize(function() {
		if($('.modal.in').length !== 0) {
			setModalMaxHeight($('.modal.in'));
		} // END if($('.modal.in').length !== 0)
	});

	/**
	 * scroll to anchor smoothly
	 */
	$('a').on('click', function(event) {
		/**
		 * Make sure this.hash has a value before overriding default behavior
		 */
		if(this.hash !== '') {
			// Prevent default anchor click behavior
			event.preventDefault();

			/**
			 * @type .hash
			 */
			var hash = this.hash;

			/**
			 * Using jQuery's animate() method to add smooth page scroll
			 * The optional number (500) specifies the number of milliseconds
			 * it takes to scroll to the specified area
			 */
			if($(hash).offset() !== undefined) {
				$('html, body').animate({
					scrollTop: $(hash).offset().top
				}, 500, function() {
					/**
					 * Add hash (#) to URL when done scrolling
					 * (default click behavior) as long as it's not #pagetop
					 */
					if(hash !== '#pagetop') {
						window.location.hash = hash;
					} // END if(hash !== '#pagetop')
				});
			} // END if($(hash).offset() !== undefined)
		} // End if(this.hash !== '')
	});

	/**
	 * Clearing the placeholder text in input fields when focused to avoid confusion
	 */
	var placeholder;
	$('input, textarea').on('focusin', function() {
		/**
		 * Saving the original placeholder txt into our variable
		 */
		placeholder = $(this).attr('placeholder');

		/**
		 *  Clearing the placeholder text
		 */
		$(this).attr('placeholder', '');
	}).on('focusout', function() {
		/**
		 * Re-writing the placeholder text from our variable
		 */
		$(this).attr('placeholder', placeholder);
	});
});