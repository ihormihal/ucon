//NAVBAR -> LEFT MENU
(function($) {

$(document).on('click', '.menu-open-bar', function(event){
	event.preventDefault();
	var menu = $('.menu-slide');
	menu.toggleClass('opened');
});

$(document).on('click', function(event){

	var onMenuClick = false;
	if(event.target.classList.contains('menu-slide') || event.target.classList.contains('menu-open-bar')){
		onMenuClick = true;
	}else{
		var parents = $(event.target).parents();
		for(var i = 0; i < parents.length; i++){
			var classList = parents[i].classList;
			if(classList.contains('menu-slide') || classList.contains('menu-open-bar')){
				onMenuClick = true;
				break;
			}
		}
	}
	//if clicked another area - close all dropdowns
	if(!onMenuClick){
		$('.menu-slide.opened').removeClass('opened');
	}
});

})(jQuery);