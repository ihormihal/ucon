(function($) {

	$.fn.imDropdown = function(options) {
		var settings = $.extend({
			selector: '.dropdown',
			toggle: '.toggle', // collection selector, optional for hover-trigger
			hover: false // trigger event
		}, options);

		settings.selector = this.selector;


		var dropdown = null; //current

		function showIn(element) {
			var animate = function() {
				if (element.hasClass('focus')) {
					element.addClass('in');
				}
			}
			element.addClass('focus');
			setTimeout(animate, 50);
		};

		function hideOut(element) {
			element.removeClass('focus in');
		};

		function closeAll() {
			$(settings.selector).each(function(index, element) {
				$(this).removeClass('focus in');
			});
		};

		$(document).on('click', function(event) {
			if (!$(event.target).parents(settings.selector)[0]) {
				closeAll();
			}
		});

		if (settings.hover) {
			var delayToHide = null;
			var delayToShow = null;
			$(document).on('mouseenter', settings.selector, function() {
				if (dropdown) {
					if ($(this).index() == dropdown.index()) {
						clearTimeout(delayToHide);
					} else {
						hideOut(dropdown);
					}
				}

				dropdown = $(this);
				delayToShow = setTimeout(showIn, 100, dropdown);
			});

			$(document).on('mouseleave', settings.selector, function() {
				delayToHide = setTimeout(hideOut, 250, $(this));
				clearTimeout(delayToShow);
			});

		} else {
			$(document).on('click', settings.selector +' '+ settings.toggle, function() {
				dropdown = $(this).parents(settings.selector);
				if (dropdown.hasClass('focus in')) {
					hideOut(dropdown);
				} else {
					showIn(dropdown);
				}
			});
		}

	};

})(jQuery);
