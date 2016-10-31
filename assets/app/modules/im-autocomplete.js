/*
 * Angular - Directive "im-autocomplete"
 * im-autocomplete - v0.6.1 - 2016-10-27
 * https://github.com/ihormihal/IM-Framework
 * autocomplete.php
 * Ihor Mykhalchenko (http://mycode.in.ua/)
 */

//delay form submit event on press Enter key
window.submitDelay = null;
document.onsubmit = function(event){
	event.preventDefault();
	function submit(){
		console.log('submit form');
		event.target.submit();
	}
	window.submitDelay = setTimeout(submit, 300);
};

angular.module('im-autocomplete', [])


.directive('imAutocompleteSingle', function() {
	return {
		restrict: 'E',
		scope: {
			name: '@',
			placeholder: '@',
			class: '@',
			url: '@',
			ngModel: '=',
			output: '=',
			updated: '=',
			disable: '=',
			onChangeClear: '='
		},
		template: '<div class="dropdown dropdown-select im-autocomplete-single" ng-class="{\'focus in\': select.visible, \'loading\': loading}">'+
			'<input ng-model="select.selected" name="{{name}}" type="hidden">'+
			'<input autocomplete="off" ng-disabled="disable" ng-model="select.search" class="full {{class}}" ng-class="{\'select\': select.empty}" type="text" placeholder="{{placeholder}}">'+
			'<div class="collection">'+
				'<ul>'+
					'<li ng-repeat="result in select.results" ng-class="{\'selected\': result.value == select.selected.value}" ng-click="select.choose($index)">{{result.text}}</li>'+
				'</ul>'+
			'</div>'+
		'</div>',
		controller: [
			'$scope', '$element', '$attrs', '$http',
			function($scope, $element, $attrs, $http) {

				var input = $element[0].getElementsByTagName('input')[0];
				var collection = $element[0].getElementsByClassName('collection')[0];
				var list = $element[0].getElementsByTagName('ul')[0];


				var config = {
					onfocus: false,
					minLength: 2
				};

				if($attrs.minLength){
					config.minLength = parseInt($attrs.minLength);
					if($attrs.minLength == "0"){
						config.minLength = 1;
						config.onfocus = true;
					}
				}

				$scope.updated = false;
				$scope.scrollmode = false;

				var textInput = $element[0].getElementsByTagName('input')[1];


				function hideSelect() {
					$scope.select.visible = false;
					$scope.$apply();
				};
				var hideDelay;


				$scope.updateSelected = function(apply){
					$scope.select.search = $scope.select.selected.text;
					$scope.output = $scope.select.selected;
					$scope.ngModel = $scope.output;
					$scope.select.value = angular.toJson($scope.select.selected);
					$scope.updated = true;
					if(apply){
						$scope.$apply();
					}
				};

				$scope.select = {
					search: '',
					selected: {},
					results : [],
					visible: false,
					empty: config.onfocus,
					currentIndex: -1,
					choose: function(index){
						$scope.select.currentIndex = index;
						$scope.select.selected = $scope.select.results[index];

						$scope.updateSelected();

						clearTimeout(hideDelay);
						$scope.select.visible = false;
						
					}
				};

				//key commands
				$element[0].onkeyup = function(event) {
					event.preventDefault();
					event.stopPropagation();
					console.log(event.keyCode);
					//key down
					if(event.keyCode == 40){
						$scope.scrollmode = true;

						if($scope.select.currentIndex >= $scope.select.results.length - 1){
							$scope.select.currentIndex = 0;
						}else{
							$scope.select.currentIndex++;
						}
						
						var li = collection.getElementsByTagName('li')[$scope.select.currentIndex];
						if(li.offsetTop > collection.offsetHeight || list.scrollTop > li.offsetTop){
							list.scrollTop = li.offsetTop;

						}

						
						$scope.select.selected = $scope.select.results[$scope.select.currentIndex];
						$scope.updateSelected(true);
						
					}
					//key up
					else if(event.keyCode == 38){
						$scope.scrollmode = true;

						if($scope.select.currentIndex == 0){
							$scope.select.currentIndex = $scope.select.results.length - 1;
						}else{
							$scope.select.currentIndex--;
						}

						var li = collection.getElementsByTagName('li')[$scope.select.currentIndex];
						if(li.offsetTop > collection.offsetHeight || list.scrollTop > li.offsetTop){
							list.scrollTop = li.offsetTop;

						}

						$scope.select.selected = $scope.select.results[$scope.select.currentIndex];
						$scope.updateSelected(true);

					}else if(event.keyCode == 13){
						$scope.scrollmode = false;
						if($scope.select.visible){
							clearTimeout(window.submitDelay);
							textInput.blur();
						}
					}else{
						$scope.scrollmode = false;
					}
				};


				//initialize
				if($attrs.value){
					$scope.select.selected = angular.fromJson($attrs.value);
					if($scope.select.selected.text){
						$scope.select.search = $scope.select.selected.text;
					}
					$scope.output = $scope.select.selected;
					$scope.ngModel = $scope.output;
				}

				$scope.$watch('ngModel', function(val){
					if(val){
						$scope.select.selected = {
							value : val.value,
							text : val.text
						};
						if($scope.select.selected.text){
							$scope.select.search = $scope.select.selected.text;
						}
						$scope.output = $scope.select.selected;
					}
				});

				textInput.onblur = function(){
					hideDelay = setTimeout(hideSelect, 200);
				};


				if(config.onfocus){
					textInput.onfocus = function(){
						loadResults();
					}
				}

				var alreadyLoaded = false;
				var loadResults = function(val){
					if(alreadyLoaded) {
						updateSelected(true);
						if(config.onfocus) return false;
					}

					val = val || '';
					var getUrl = $scope.url;
					if(getUrl.indexOf('?') !== -1){
						getUrl += '&search='+val;
					}else{
						getUrl += '?search='+val;
					}
					//ajax
					$scope.loading = true;
					$http({
						method: 'GET',
						url: getUrl
					}).then(function(response) {
						$scope.loading = false;
						$scope.select.results = response.data;
						if($scope.select.results.length){
							$scope.select.visible = true;
						}
					}, function(error) {
						console.log(error);
					});
				}

				$scope.$watch('url', function(){
					alreadyLoaded = false;
				});

				$scope.$watch('select.search', function(val){
					if($scope.scrollmode){
						return false;
					}
					if($scope.onChangeClear){
						$scope.onChangeClear = {value:'', text: ''};
					}
					var chosen = true;
					if($scope.select.selected){
						if(val !== $scope.select.selected.text){
							chosen = false;
							$scope.select.selected = {text: val};
							$scope.output = $scope.select.selected;
							$scope.ngModel = $scope.output;
						}
					}
					if(val == ''){
						$scope.select.selected = {};
						$scope.output = {};
						$scope.ngModel = $scope.output;
					}
					if(val.length >= parseInt(config.minLength) && !chosen){
						alreadyLoaded = false;
						loadResults(val);
					}else{
						$scope.select.results = [];
					}
				});

				$scope.$watch('select.selected', function(val){
					input.value = angular.toJson(val);
					$scope.ngModel.type = 'autocomplete';
				});
			}
		]
	};
})



.directive('imAutocompleteMultiple', function() {
	return {
		restrict: 'E',
		scope: {
			name: '@',
			placeholder: '@',
			class: '@',
			url: '@',
			output: '=',
			ngModel: '=',
			updated: '='
		},
		template: '<div class="dropdown dropdown-select im-autocomplete-multiple" ng-class="{\'focus in\': select.visible}">'+
			'<input ng-model="select.value" name="{{name}}" type="hidden">'+
			'<div class="selection {{class}}" ng-class="{\'focus\': select.focus, \'empty\': select.empty, \'select\': select.onfocus, \'loading\': loading}">'+
				'<div ng-repeat="item in select.selected" class="item" ng-class="{\'custom\': !item.value}">{{item.text}}<i class="fa fa-times" ng-click="select.remove($index)"></i></div>'+
				'<input autocomplete="off" ng-model="select.search" type="text" placeholder="{{placeholder}}">'+
			'</div>'+
			'<div class="collection">'+
				'<ul>'+
					'<li ng-repeat="result in select.results track by $index" ng-click="select.choose($index)" ng-class="{\'selected\': select.currentIndex == $index}">{{result.text}}</li>'+
				'</ul>'+
			'</div>'+
		'</div>',
		controller: [
			'$scope', '$element', '$attrs', '$http', '$filter', '$timeout',
			function($scope, $element, $attrs, $http, $filter, $timeout) {

				var input = $element[0].getElementsByTagName('input')[0]; //hidden
				var textInput = $element[0].getElementsByTagName('input')[1]; //for search
				var selection = $element[0].getElementsByClassName('selection')[0];
				var collection = $element[0].getElementsByClassName('collection')[0];
				var list = $element[0].getElementsByTagName('ul')[0];

				var config = {
					onfocus: false,
					minLength: 2,
					custom: 'allow', //only, deny
					customChar: ','
				};

				$scope.updated = false; //onupdate flag
				//we need to close selection list on blur but there are the problem
				//we need to add delay after choose item from collection (otherwise the selection list is closed immediately on blur)
				var blurDelay = null;

				//configure "custom" option
				if($attrs.custom){
					if($attrs.custom == 'true'){
						config.custom = 'only';
					}else if($attrs.custom == 'false'){
						config.custom = 'deny';
					}else{
						config.custom = 'allow';
					}
				}

				//if minLength == 0
				if($attrs.minLength){
					config.minLength = parseInt($attrs.minLength);
					if(config.minLength == 0){
						config.onfocus = true;
					}
				}

				//prevent form submittion on press Enter key and add custom if input notempty
				textInput.onkeyup = function(event) {
					if(event.keyCode == 13){
						clearTimeout(window.submitDelay);
						if((config.custom == 'allow' || config.custom == 'only') && $scope.select.search.length > 0){
							//add custom if selection not opened and index is valid
							if(!($scope.select.visible && $scope.select.currentIndex >= 0)){
								$scope.select.selected.push({text: $scope.select.search});
								removeDubles();
								$scope.select.search = '';
							}
							
						}
					}
				};

				//focus to textInput on selection click
				selection.onclick = function(event){
					event.stopPropagation();
					if(event.target == selection){
						textInput.focus();
					}
				};

				var removeDubles = function(){
					var temp = [];
					for (var i = 0; i < $scope.select.selected.length; i++) {
						var text = $scope.select.selected[i].text;
						if(temp.indexOf(text) !== -1){
							$scope.select.selected.splice(i, 1);
						}
						temp.push(text);
					}
				};

				/* Main Object */
				$scope.select = {
					search: '',
					value: '',
					currentIndex: -1,
					selected: [],
					results : [],
					visible: false,
					focus: false,
					onfocus: config.onfocus,
					empty: true,
					choose: function(index){
						$scope.select.currentIndex = index;
						clearTimeout(blurDelay); //allow to close after selection
						var selected = $scope.select.results[index];

						$scope.select.selected.push(selected);
						$scope.select.search = ''; //clear search field
						updateSelected(false); //not show selection list after

						$scope.select.focus = false;
						textInput.blur();
					},
					remove: function(index){
						$scope.select.selected.splice(index,1);
						updateSelected(false); //not show selection list after
						alreadyLoaded = false;
					}
				};

				/* Initialize */

				//from value
				if($attrs.value){
					var initValues = angular.fromJson($attrs.value);
					$scope.select.selected = initValues;
				}
				//from ngModel
				$scope.$watch('ngModel', function(val){
					if(val){
						$scope.select.selected = []
						for (var i = 0; i < val.length; i++) {
							$scope.select.selected.push({
								value : val[i].value,
								text : val[i].text
							});
						}
					}
				});

				var afterBlur = function(){
					$scope.select.focus = false;
					$scope.select.visible = false;
					$scope.$apply();
				};

				textInput.onblur = function(e){
					blurDelay = setTimeout(afterBlur, 200);
				};

				textInput.onfocus = function(){
					$scope.select.focus = true;
					if(config.onfocus){
						loadResults(true);
					}
				};

				//key commands
				$element[0].onkeyup = function(event) {
					event.preventDefault();
					event.stopPropagation()
					//console.log(event.keyCode);
					//key down
					if(event.keyCode == 40){

						if($scope.select.currentIndex >= $scope.select.results.length - 1){
							$scope.select.currentIndex = 0;
						}else{
							$scope.select.currentIndex++;
						}

						var li = collection.getElementsByTagName('li')[$scope.select.currentIndex];
						if(li.offsetTop > collection.offsetHeight || list.scrollTop > li.offsetTop){
							list.scrollTop = li.offsetTop;

						}
					}
					//key up
					else if(event.keyCode == 38){

						if($scope.select.currentIndex == 0){
							$scope.select.currentIndex = $scope.select.results.length - 1;
						}else{
							$scope.select.currentIndex--;
						}

						var li = collection.getElementsByTagName('li')[$scope.select.currentIndex];
						if(li.offsetTop > collection.offsetHeight || list.scrollTop > li.offsetTop){
							list.scrollTop = li.offsetTop;

						}
					//enter
					}else if(event.keyCode == 13){
						clearTimeout(window.submitDelay);
						if($scope.select.visible && $scope.select.currentIndex >= 0){
							$scope.select.choose($scope.select.currentIndex);
						}
					}
					$scope.$apply();

				};

				
				function updateSelected(showResults){
					//remove selected from results list
					var temp = [];
					for(var i = 0; i < $scope.select.results.length; i++){
						var exists = false;
						for(var j = 0; j < $scope.select.selected.length; j++){
							if($scope.select.selected[j].value == $scope.select.results[i].value){
								$scope.select.selected[j] = $scope.select.results[i]; //update selected by value just in case
								exists = true;
								break;
							}
						}
						if(!exists){
							temp.push($scope.select.results[i]);
						}
					}
					//cutting selected from dropdown
					$scope.select.results = temp;
					if($scope.select.results.length && showResults){
						$timeout(function() {
							$scope.select.visible = true;
						}, 0);
					}else{
						$timeout(function() {
							$scope.select.visible = false;
						}, 0);
					}
				};

				var alreadyLoaded = false;
				var loadResults = function(showResults, val){

					if(alreadyLoaded) {
						updateSelected(true);
						if(config.onfocus) return false;
					}

					val = val || '';
					showResults = showResults ? true : false;

					var getUrl = $scope.url;
					if(getUrl.indexOf('?') !== -1){
						getUrl += '&search='+val;
					}else{
						getUrl += '?search='+val;
					}

					//ajax
					$scope.loading = true;
					$http({
						method: 'GET',
						url: getUrl
					}).then(function(response) {
						$scope.loading = false;
						$scope.select.results = response.data;
						updateSelected(showResults);
						alreadyLoaded = true;

					}, function(error) {
						console.log(error);
					});
				};

				//pre-load all results
				if(config.onfocus){
					loadResults(false);
				}

				//olways update if url-parameter changed
				$scope.$watch('url', function(){
					alreadyLoaded = false;
				});

				//add custom element after delimiter input
				var addCustom = function(val){
					if(val.length > 1 && val.substr(val.length - 1) == config.customChar){
						$scope.select.selected.push({text: val.substring(0, val.length - 1)});
						removeDubles();
						$scope.select.search = '';
					}
				};


				var firstInit = true;
				$scope.$watch('select.search', function(val){

					//not call this function on initialing
					if(firstInit){
						firstInit = false;
						return false;
					}

					if(val.length >= config.minLength){
						alreadyLoaded = false;
						//add custom
						if(config.custom == 'allow'){
							addCustom(val);
							loadResults(true, val);
						}else if(config.custom == 'only'){
							addCustom(val);
						}else if(config.custom == 'deny'){
							loadResults(true, val);
						}
					}else{
						$scope.select.visible = false;
					}
				});

				$scope.$watch('select.selected', function(val){

					$scope.select.empty = val.length ? false : true;

					$scope.output = val;
					$scope.select.value = input.value = angular.toJson(val);
					$scope.updated = true;

					//add 'autocomplete' to ngModel
					var temp = [];
					for (var i = 0; i < val.length; i++) {
						temp.push({
							value : val[i].value,
							text : val[i].text,
							type : 'autocomplete'
						});
					}
					$scope.ngModel = temp;

				}, true);
			}
		]
	};
})

;