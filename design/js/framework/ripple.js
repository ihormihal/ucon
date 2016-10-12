var cssTransitionEnd = 'transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd';
var cssAnimationEnd = 'webkitAnimationEnd oanimationend msAnimationEnd animationend';

$(document).on('click', '.ripple, .btn-mt', function(event){
	var that, ink, d, x, y;
	that = $(this);
	if(that.find('.ink').length == 0){
		that.prepend('<span class="ink"></span>');
	}
	ink = that.find('.ink');
	ink.removeClass('animate');
	if(!ink.height() && !ink.width())
	{
		d = Math.max(that.outerWidth(), that.outerHeight());
		ink.css({height: d, width: d});
	}
	x = event.pageX - that.offset().left - ink.width()/2;
	y = event.pageY - that.offset().top - ink.height()/2;
	ink.css({top: y+'px', left: x+'px'}).addClass('animate');
	that.on(cssAnimationEnd, '.ink', function(){
		$(this).remove();
	});
});

(function($) {
	$.fn.customPlugin = function(options){
		var settings = $.extend({

		}, options);

		//customPlugin
		$(this).click(function(e){

		});

	};
})(jQuery);