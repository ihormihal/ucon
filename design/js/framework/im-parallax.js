/*
 * Universal Single-Layer Parallax
 * parallax - v0.1.1 - 2016-03-01
 * https://github.com/ihormihal/IM-Framework
 * Ihor Mykhalchenko (http://mycode.in.ua)
 */


(function($) {
	$.fn.imParallax = function(options){
		var settings = $.extend({
			speed: 0.8, // 0 -> 1 : slowly, 1 -> 2 : falster
			minWidth : 992
		}, options);

		var that = this;

		var main = function(){

			$('.parallax-image').remove();

			if($(window).width() < settings.minWidth){
				$(that).each(function(index,element){
					var src = element.getAttribute('data-image');
					element.style.backgroundImage = 'url('+src+')';
				});
			}else{
				$(that).each(function(index,element){
					var item = {};
					element.style.backgroundImage = '';
					var thisSpeed = settings.speed;

					var src = element.getAttribute('data-image');
					var speed = element.getAttribute('data-speed');

					if(speed){
						speed = Math.abs(parseFloat(speed));
						if(speed > 2){
							console.log('Too mutch speed for parallax (use 0...2)!'); //default value used
						}else if(speed == 1){
							return false; //no sense run parallax
						}else{
							thisSpeed = speed;
						}
					}

					var prallaxHeight = element.offsetHeight; //container height
					var prallaxTop = $(element).offset().top; //page top -> container

					item.parallax = document.createElement('div');
					item.parallax.className = "parallax-image";
					item.parallax.style.height = prallaxHeight + 'px';

					item.image = document.createElement('img');
					item.image.src = src;

					item.parallax.appendChild(item.image);

					document.body.appendChild(item.parallax);


					var moveImage = function() {
						var scrTop = $(window).scrollTop(); //scrolling position document top -> window top
						var scrBottom = scrTop + $(window).innerHeight(); //scrolling position document top -> window bottom
						var origin = prallaxTop - scrTop;

						var originScroll = 'translate3d(0, '+ origin +'px, 0)';
						var parallaxScroll = 'translate3d(0, '+  parseInt(origin*(thisSpeed-1)) +'px, 0)';


						var topBorderVisible = false; //image top edge visibility
						var bottomBorderVisible = false; //image bottom edge visibility

						var fixOffset = 100; //scroll step in Chrome

						if(prallaxTop > (scrTop - fixOffset) && prallaxTop < (scrBottom + fixOffset)){
							topBorderVisible = true;
						}
						if(prallaxTop + prallaxHeight > (scrTop - fixOffset) && prallaxTop + prallaxHeight < (scrBottom + fixOffset)){
							bottomBorderVisible = true;
						}

						//run if only parallax is visible
						if(topBorderVisible || bottomBorderVisible || needReload){
							item.parallax.style.transform = originScroll;
							item.parallax.style['-webkit-transform'] = originScroll;
							item.image.style.transform = parallaxScroll;
							item.image.style['-webkit-transform'] = parallaxScroll;
						}

					};

					moveImage();

					$(window).scroll(function() {
						moveImage();
					});
				});
			}

			needReload = false;
		};

		var needReload = true;

		main();

		$(window).resize(function() {
			needReload = true;
			main();
		});
	};
})(jQuery);