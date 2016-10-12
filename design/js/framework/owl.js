(function($) {

$(function() {
	//OWL Carousel
	$('.carousel').each(function(){
		var carousel = $(this);
		var xs = parseInt($(this).data('xs')) || 1;
		var sm = parseInt($(this).data('sm')) || 2;
		var md = parseInt($(this).data('md')) || 3;
		var lg = parseInt($(this).data('lg')) || 4;
		var xl = parseInt($(this).data('xl')) || 5;

		var navigation = $(this).attr('data-navigation') == 'true' ? true : false;
		var pagination = $(this).attr('data-pagination') == 'true' ? true : false;

		var autoplay = false;
		var data_autoplay = $(this).attr('data-autoplay');
		if(data_autoplay !== "false"){
			var autoplay = data_autoplay;
		}

		var scrollPerPage = $(this).attr('data-scrollPerPage') == 'true' ? true : false;

		carousel.owlCarousel({
			navigation: navigation,
			pagination: pagination,
			navigationText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
			scrollPerPage: scrollPerPage,
			autoPlay: autoplay,
			itemsCustom: [
				[0,    xs],
				[361,  sm],
				[769,  md],
				[1008, lg],
				[1265, xl]
			]
		});
	});

	//OWL Slider
	$('.slider').each(function(){
		var slider = $(this);
		var navigation = $(this).data('navigation') || false;
		var pagination = $(this).data('pagination') || false;
		var autoplay = false;
		var data_autoplay = $(this).attr('data-autoplay');
		if(data_autoplay !== "false"){
			var autoplay = data_autoplay;
		}

		slider.owlCarousel({
			navigation: navigation,
			pagination: pagination,
			navigationText: ['<i class="fa fa-angle-left">', '<i class="fa fa-angle-right">'],
			singleItem: true,
			autoPlay: autoplay,
			afterAction : function() {
				var currentIndex = this.currentItem;
				var slides = slider.find('.owl-item');
				slides.each(function(index){
					if(index !== currentIndex){
						$(this).find('.animated').each(function(){
							$(this).removeClass($(this).data('animated'));
						});
					}
				});
				slides.eq(this.currentItem).find('.animated').each(function(){
					$(this).addClass($(this).data('animated'));
				});
			}
		});
	});

	//OWL Carousel custom nav
	$('.owl-custom-nav').on('click', '.owl-prev, .owl-next', function(){
		var target = $(this).parent('.owl-custom-nav').data('target');
		var owl = $(target).data('owlCarousel');
		if($(this).hasClass('owl-prev')){
			owl.prev();
		}else if($(this).hasClass('owl-next')){
			owl.next();
		}
	});
});

})(jQuery);