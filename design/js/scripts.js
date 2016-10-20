var cssTransitionEnd = 'transitionend webkitTransitionEnd oTransitionEnd MSTransitionEnd';

/* WOW plugin - animate when Scrolling
var Wow = new WOW({
	boxClass: 'wow',
	animateClass: 'animated',
	offset: 0,
	mobile: true,
	live: true
});
*/

//Wow.init();

//window resize
$(window).on('resize', function(){

});

//all images loaded
$(window).on('load', function(){
	if($().imParallax) $('.parallax').imParallax();
});

$(document).on('submit', 'form.ajax-form', function(event) {
	event.preventDefault();

	var form = $('form.ajax-form');
	var button = $('.btn-loading');

	button.addClass('load');
	
	$.ajax({
		url: form.attr('action'),
		method: form.attr('method'),
		data: form.serialize()
	}).done(function(response){
		button.removeClass('load');
		console.log(response);
		message = 'Operation succeeded!';
		if(response.status == 'success'){
			message = response.message || message;
			toastr.success(message, 'Success!');
		}else if(response.status == 'warning'){
			message = response.message || 'Something wrong...';
			toastr.warning(message, 'Warning!');
		}else if(response.status == 'error'){
			message = response.message || 'An error has occurred!';
			toastr.error(message, 'Error!');
		}else{
			message = 'Undefined server error!';
			toastr.error(message, 'Error!');
		}
	}).fail(function(){
		button.removeClass('load');
		var message = 'An error has occurred!';
		toastr.error(message, 'Error!');
	});
	

});

// $(document).on('click', '.popup-img', function() {
// 	var src = $(this).attr('src');
// 	$.fancybox.open({
// 		type: 'image',
// 		href: src,
// 		fitToView: true,
// 		autoSize: true,
// 		width: 'false',
// 		height: 'false',
// 		padding: 0,
// 		helpers     : {
// 			overlay : {
// 				opacity : 0.4,
// 				locked: true
// 			}
// 		}
// 	});
// });

$(document).on('click', '.image-preview-box a', function(e) {
	e.preventDefault();
	var src = $(this).attr('href');
	$.fancybox.open({
		type: 'image',
		href: src,
		fitToView: true,
		autoSize: true,
		width: 'false',
		height: 'false',
		padding: 0,
		helpers     : {
			overlay : {
				opacity : 0.4,
				locked: true
			}
		}
	});
});

//for jQuery dataTable
$(document).on('click', 'tr.clickable', function() {
	var link = $(this).attr('data-href');
	if(link) window.document.location = link;
});

$(document).on('click', 'table.clickable tr', function() {
	var link = $(this).attr('data-href');
	if(link) window.document.location = link;
});

//input-icon fix
$(document).on('click', '.input-icon i', function() {
	$(this).parent().find('input').focus();
});


$(function() {

	//Simple fancybox
	$('.fancybox').fancybox({
		type: 'image',
		fitToView: true,
		autoSize: true,
		width: false,
		height: false,
		padding: 0,
		wrapCSS: 'popup',
		closeBtn: true,
		helpers     : {
			overlay : {
				opacity : 0.4,
				locked: true
			}
		}
	});

	/* CountUp Plugin */
	/*
	$('.counter > span').each(function(){
		var endVal = parseInt($(this).text());
		new CountUp(this,0,endVal,0,3).start();
	});
	*/

	//Select autocomplete
	if($().imSelect) {
		$('select.multiselect').imSelect();
	}
	//Dropdown
	$('.nav-inline .dropdown').imDropdown({
		hover: true
	});

	//Code highlighter
	$('pre code').each(function(i, block) {
		hljs.highlightBlock(block);
	});
	//Code popup
	$('.show-code').fancybox();
	//jQuery Datatable
	if($().DataTable){
		$('.datatable').DataTable({
			dom : dataTablesDom,
			language : dataTablesLng
		});
	}
	//Floating label init
	$('.floating-label').each(function(){
		if($(this).find('input, textarea').val() !== ''){
			$(this).addClass('focus');
		}
	});


	//Google map
	//with single point
	$('.map.single').each(function(){
		var container = this;
		var location = {lat: 50, lng: 30};
		if($(container).data('location')){
			var loc = $(container).data('location');
			location.lat = parseFloat(loc.lat);
			location.lng = parseFloat(loc.lng);
		}
		if($(container).data('lat') && $(container).data('lng')){
			location.lat = parseFloat($(container).data('lat'));
			location.lng = parseFloat($(container).data('lng'));
		}
		var latlng = new google.maps.LatLng(location.lat, location.lng);
		var mapOptions = {
			zoom: parseInt($(container).data('zoom')) || 17,
			center: latlng,
			zoomControl: true,
			mapTypeControl: false,
			streetViewControl: false,
			scrollwheel: $(container).data('scroll') || false,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		if($(container).data('color')){
			mapOptions.styles = [{
				stylers: [
				{
					hue: $(container).data('color')
				}, {
					saturation: 20
				}, {
					gamma: 0.5
				}]
			}];
		}
		var map = new google.maps.Map(container, mapOptions);

		var marker = new google.maps.Marker({
			position: latlng,
			map: map,
			icon: $(container).data('marker')
		});
	});

	//with many points
	$('.map.multiple').each(function(){
		var container = this;
		var points = $.parseJSON($(container).html()).points;
		var mapOptions = {
			zoom: parseInt($(container).data('zoom')),
			zoomControl: true,
			mapTypeControl: false,
			streetViewControl: false,
			scrollwheel: true,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		var map = new google.maps.Map(container, mapOptions);
		var bounds = new google.maps.LatLngBounds();

		var infowindow = new google.maps.InfoWindow();
		var markers = [];
		for (i = 0; i < points.length; i++) {
			if(points[i]){
				var marker = new google.maps.Marker({
					position: new google.maps.LatLng(parseFloat(points[i].lat), parseFloat(points[i].lng)),
					map: map,
					icon: $(container).data('marker'),
					id: points[i].id
				});
				markers.push(marker);
				google.maps.event.addListener(marker,'click',function(){
					loadInfoWindow(this);
				});
				bounds.extend(marker.position);
			}
		}
		map.fitBounds(bounds);
		google.maps.event.addListenerOnce(map, 'idle', function(){
			if(markers.length == 0){
				map.setZoom(1);
			}
			if(markers.length == 1){
				map.setZoom(mapOptions.zoom);
			}
		});

		function loadInfoWindow(marker){
			$.ajax({
				url: $(container).data('ajax-window'),
				data: {id: marker.id}
			}).done(function(data){
				infowindow.close();
				infowindow.setContent(data);
				infowindow.open(map,marker);
			});
		}
	});

	/* AJAX MAP START */
	var ajaxMap = function(container){
		var self = this;
		self.container = container;
		var center = $(container).data('center').split(',');
		self.options = {
			oneZoom : parseInt($(container).data('one-zoom')),
			zoom: parseInt($(container).data('zoom')),
			center: new google.maps.LatLng(
				parseFloat(center[0].trim()),
				parseFloat(center[1].trim())
			),
			zoomControl: true,
			mapTypeControl: false,
			streetViewControl: false,
			scrollwheel: true,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		self.map = new google.maps.Map(self.container, self.options);
		self.markers = [];
		self.load = function(points){
			for(var i = 0; i < self.markers.length; i++){
				self.markers[i].setMap(null);
			}
			self.markers = [];
			self.bounds = new google.maps.LatLngBounds();
			self.infowindow = new google.maps.InfoWindow();
			for (i = 0; i < points.length; i++) {
				var marker = new google.maps.Marker({
					position: new google.maps.LatLng(parseFloat(points[i].lat), parseFloat(points[i].lng)),
					map: self.map,
					icon: $(container).data('marker'),
					id: points[i].id
				});
				self.markers.push(marker);
				google.maps.event.addListener(marker,'click',function(){
					loadInfoWindow(this);
				});
				self.bounds.extend(marker.position);
			}
			self.map.fitBounds(self.bounds);
			google.maps.event.addListenerOnce(self.map, 'idle', function(){
				if(self.markers.length == 0){
					self.map.setCenter(self.options.center);
					self.map.setZoom(self.options.zoom);
				}
				if(self.markers.length == 1){
					self.map.setZoom(self.options.oneZoom);
				}
			});
			function loadInfoWindow(marker){
				$.ajax({
					url: $(container).data('ajax-window'),
					data: {id: marker.id}
				}).done(function(data){
					self.infowindow.close();
					self.infowindow.setContent(data);
					self.infowindow.open(self.map,marker);
				});
			}
		};
	};


	var loadResources = function(options){
		if(options.resources == ''){
			$('#ajax-resources').html('');
		}else{
			spinner.spin(spinnerTarget);
			$.ajax({
				url: $('#ajax-resources').data('ajax'),
				data: options,
				method: 'get'
			}).done(function(response){
				$('#ajax-resources').html('');
				spinner.stop();
				$('#ajax-resources').html(response);
			});
		}
	};

	var loadAjaxPoints = function(){
		var form = $('#map-filter');
		$.ajax({
			url: form.attr('action'),
			data: form.serialize(),
			method: form.attr('method')
		}).done(function(response){
			var result = $.parseJSON(response);
			ajaxFilterMap.load(result.points);
			loadResources({
				'tpl' : 'tpl.cardBox',
				'parents' : 8,
				'includeTVs' : 'tv_image',
				'resources' : result.ids.join(',')
			});
		});
	};

	if($('#ajax-map').length > 0){
		var ajaxFilterMap = new ajaxMap(document.getElementById('ajax-map'));
		loadAjaxPoints();
	}

	$('#map-filter').on('change', 'select,input', function(){
		loadAjaxPoints();
	});

	/* AJAX MAP END */

	//TABS
	$('.tab-control').each(function(){
		var control = $(this);
		var target = $(this).data('target');
		$(this).on('click','li',function(){
			control.find('.active').removeClass('active');
			$(this).addClass('active');
			var activeTab = $(target+' .tab:eq('+$(this).index()+')');
			var runAnimation = function(){
				activeTab.addClass('in');
			}
			$(target+' .tab.active.in').removeClass('active in');
			activeTab.addClass('active');
			setTimeout(runAnimation, 50);
		});
	});


});

//accordion menu
$(document).on('click touchstart', '.aside-menu li.parent, .aside-menu li.dropdown', function(e) {
	e.stopPropagation();
	//console.log(e.target.nodeName);
	if (e.target.nodeName === 'A') {
		var li = $(e.target).parent('li');
		var ul = li.find('> ul');
		if (ul.is(':visible')) {
			ul.slideUp(250, function() {
				li.removeClass('active');
			});
		} else {
			ul.slideDown(250, function() {
				li.addClass('active');
			});
		}
	} else if (e.target.nodeName === 'SPAN' || e.target.nodeName === 'I'){
		var url = $(e.target).parent('a').attr('href');
		document.location.href = url;
	}
});


//Floating-label
$(document).on('focusin', '.floating-label input, .floating-label textarea', function(){
	$('.floating-label').addClass('focus');
});
$(document).on('focusout', '.floating-label input, .floating-label textarea', function(){
	if($(this).val() == ''){
		$('.floating-label').removeClass('focus');
	}
});
//END Floating-label