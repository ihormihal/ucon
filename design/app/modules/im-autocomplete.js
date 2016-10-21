/*
 * Angular - Directive "im-autocomplete"
 * im-autocomplete - v0.4.11 - 2016-10-20
 * https://github.com/ihormihal/IM-Framework
 * autocomplete.php
 * Ihor Mykhalchenko (http://mycode.in.ua/)
 */

var submitDelay = null;
document.onsubmit = function(event){
	event.preventDefault();
	function submit(){
		event.target.submit();
	}
	submitDelay = setTimeout(submit, 100);
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
			disabled: '=',
			onChangeClear: '='
		},
		template: '<div class="dropdown dropdown-select im-autocomplete-single" ng-class="{\'focus in\': select.visible, \'loading\': loading}">'+
			'<input ng-model="select.selected" name="{{name}}" type="hidden">'+
			'<input autocomplete="off" ng-disabled="disabled" ng-model="select.search" class="full {{class}}" ng-class="{\'select\': select.empty}" type="text" placeholder="{{placeholder}}">'+
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
				var hideSelectDelay;


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

						clearTimeout(hideSelectDelay);
						$scope.select.visible = false;
						
					}
				};

				//key commands
				$element[0].onkeyup = function(event) {
					event.preventDefault();
					event.stopPropagation();
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
							clearTimeout(submitDelay);
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
						if(!val.hasOwnProperty('type')){
							val.type = 'autocomplete';
							$scope.ngModel = val;
						}
						val.type = 'autocomplete';
						$scope.select.selected = val;
						if($scope.select.selected.text){
							$scope.select.search = $scope.select.selected.text;
						}
						$scope.output = $scope.select.selected;
					}
				});

				textInput.onblur = function(){
					hideSelectDelay = setTimeout(hideSelect, 200);
				};


				$scope.clear = function(param){
					console.log(param);
				};

				if(config.onfocus){
					textInput.onfocus = function(){
						$scope.loadResults();
					}
				}

				var alreadyLoaded = false;
				$scope.loadResults = function(val){
					if(alreadyLoaded && config.onfocus) {
						updateSelected();
						return false;
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
						$scope.loadResults(val);
					}else{
						$scope.select.results = [];
					}
				});

				$scope.$watch('select.selected', function(val){
					input.value = angular.toJson(val);
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
				'<input autocomplete="off" ng-model="select.search" type="text" placeholder="{{placeholder}}" ng-focus="select.focus = true">'+
			'</div>'+
			'<div class="collection">'+
				'<ul>'+
					'<li ng-repeat="result in select.results track by $index" ng-click="select.choose($index)" ng-class="{\'selected\': select.currentIndex == $index}">{{result.text}}</li>'+
				'</ul>'+
			'</div>'+
		'</div>',
		controller: [
			'$scope', '$element', '$attrs', '$http', '$filter',
			function($scope, $element, $attrs, $http, $filter) {

				var input = $element[0].getElementsByTagName('input')[0]; //hidden
				var textInput = $element[0].getElementsByTagName('input')[1]; //for search
				var collection = $element[0].getElementsByClassName('collection')[0];
				var list = $element[0].getElementsByTagName('ul')[0];

				var config = {
					onfocus: false,
					minLength: 2,
					custom: 'allow', //only, deny
					customChar: ','
				};


				$scope.updated = false;
				$scope.scrollmode = false;
				var blurDelay = null;

				if($attrs.custom){
					if($attrs.custom == "true"){
						config.custom = 'only';
					}else if($attrs.custom == "false"){
						config.custom = 'deny';
					}
				}

				if($attrs.minLength){
					config.minLength = parseInt($attrs.minLength);
					if($attrs.minLength == "0"){
						config.minLength = 1;
						config.onfocus = true;
					}
				}

				//key commands
				$element[0].onkeyup = function(event) {
					event.preventDefault();
					event.stopPropagation()
					console.log(event.keyCode);
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
						
					}else if(event.keyCode == 13){
						if($scope.select.visible){
							clearTimeout(submitDelay);
							$scope.select.choose($scope.select.currentIndex);
						}
					}
					$scope.$apply();

				};

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
						clearTimeout(blurDelay);
						var selected = $scope.select.results[index];
						selected.type = 'autocomplete';

						$scope.select.selected.push(selected);
						$scope.select.search = ''; //clear search field
						updateSelected(true);
						$scope.select.visible = false;
					},
					remove: function(index){
						$scope.select.selected.splice(index,1);
						updateSelected(false);
						$scope.select.visible = false;
					}
				};

				//initialize
				if($attrs.value){
					var initValues = angular.fromJson($attrs.value);
					for (var i = 0; i < initValues.length; i++) {
						initValues[i].type = 'autocomplete';
					}
					$scope.select.selected = initValues;
				}
				$scope.$watch('ngModel', function(val){
					if(val){
						for (var i = 0; i < val.length; i++) {
							val[i].type = 'autocomplete';
						}
						$scope.select.selected = val;
						//$scope.output = $scope.select.selected;
					}
				});


				textInput.onfocus = function(){
					console.log('focus');
					$scope.select.focus = true;
					// if(config.onfocus){
					// 	$scope.loadResults();
					// }
				};

				var makeBlur = function(){
					$scope.select.focus = false;
					$scope.select.visible = false;
					$scope.$apply();
				};

				textInput.onblur = function(e){
					blurDelay = setTimeout(makeBlur, 200);
				};

				function updateSelected(showResults){
					//remove selected from results list
					var temp = [];
					for(var i = 0; i < $scope.select.results.length; i++){
						var exists = false;
						for(var j = 0; j < $scope.select.selected.length; j++){
							if($scope.select.selected[j].value == $scope.select.results[i].value){
								$scope.select.selected[j] = $scope.select.results[i];
								exists = true;
							}
						}
						if(!exists){
							temp.push($scope.select.results[i]);
						}
					}
					$scope.select.results = temp;
					if($scope.select.results.length && showResults){
						$scope.select.visible = true;
					}
				};

				var alreadyLoaded = false;
				$scope.loadResults = function(val){
					if(alreadyLoaded && config.onfocus) {
						updateSelected(true);
						return false;
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
						alreadyLoaded = true;
						$scope.loading = false;
						$scope.select.results = response.data;
						updateSelected(true);
					}, function(error) {
						console.log(error);
					});
				};

				//pre-load all results
				if(config.onfocus){
					$scope.loadResults();
				}


				$scope.$watch('url', function(){
					alreadyLoaded = false;
				});

				$scope.addCustom = function(val){
					if(val == config.customChar){
						$scope.select.search = '';
					}else if(val.length > 1 && val.substr(val.length - 1) == config.customChar){
						$scope.select.selected.push({text: val.substring(0, val.length - 1), type: 'autocomplete'});
						$scope.select.search = '';
					}
				};

				$scope.$watch('select.search', function(val){
					if($scope.scrollmode){
						return false;
					}
					if(val.length >= config.minLength){
						//add custom
						if(config.custom == 'allow'){
							$scope.addCustom(val);
							$scope.loadResults(val);
						}else if(config.custom == 'only'){
							$scope.addCustom(val);
						}else if(config.custom == 'deny'){
							$scope.loadResults(val);
						}
						
					}
				});

				$scope.$watch('select.selected', function(val){

					if(val.length){
						$scope.select.empty = false;
					}else{
						$scope.select.empty = true;
					}

					$scope.output = val;
					$scope.ngModel = val;
					$scope.select.value = angular.toJson(val);
					input.value = $scope.select.value;
					$scope.updated = true;

				}, true);
			}
		]
	};
})

;