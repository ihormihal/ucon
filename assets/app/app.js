angular.module('app', ['im-dataTable', 'im-imgUpload', 'im-progress', 'im-autocomplete', 'im-datepicker'])

.config([function () {

}])

.run([function (){

}])

.controller('mainController', ['$scope', function($scope){
	$scope.toalias = '';
	$scope.alias = '';

	$scope.translit = function (str) {
		str = str.toLowerCase();
		var space = '-';
		var transl = {
			'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'e', 'ж': 'zh', 
			'з': 'z', 'и': 'i', 'й': 'j', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n',
			'о': 'o', 'п': 'p', 'р': 'r','с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'h',
			'ц': 'c', 'ч': 'ch', 'ш': 'sh', 'щ': 'sh','ъ': space, 'ы': 'y', 'ь': space, 'э': 'e', 'ю': 'yu', 'я': 'ya',
			' ': space, '_': space, '`': space, '~': space, '!': space, '@': space,
			'#': space, '$': space, '%': space, '^': space, '&': space, '*': space, 
			'(': space, ')': space,'-': space, '\=': space, '+': space, '[': space, 
			']': space, '\\': space, '|': space, '/': space,'.': space, ',': space,
			'{': space, '}': space, '\'': space, '"': space, ';': space, ':': space,
			'?': space, '<': space, '>': space, '№':space
		};
		var result = '';
		var curent_sim = '';
		for (i = 0; i < str.length; i++) {
		    // Если символ найден в массиве то меняем его
		    if (transl[str[i]] != undefined) {
		        if (curent_sim != transl[str[i]] || curent_sim != space) {
		            result += transl[str[i]];
		            curent_sim = transl[str[i]];
		        }
		    }
		    // Если нет, то оставляем так как есть
		    else {
		        result += str[i];
		        curent_sim = str[i];
		    }
		}
		function TrimStr(s) {
			s = s.replace(/^-/, '');
			return s.replace(/-$/, '');
		}
		return TrimStr(result);;
	};
	
	$scope.$watch('toalias', function(val){
		$scope.alias = $scope.translit(val);
	});
}])

.directive('ngInitial', function() {
	return {
		restrict: 'A',
		controller: [
			'$scope', '$element', '$attrs', '$parse',
			function($scope, $element, $attrs, $parse) {
				var getter, setter, val;
				val = $attrs.ngInitial || $element[0].defaultValue;
				if ($element[0].tagName == 'SELECT') {
					val = $element[0].value;
				}
				getter = $parse($attrs.ngModel);
				setter = getter.assign;
				try{
					setter($scope, val);
				} catch (e) {
					console.log(e);
				}
			}
		]
	};
})

.directive('jsonCollection', function() {
	return {
		restrict: 'A',
		scope: true,
		controller: [
			'$scope', '$element', '$attrs', '$parse',
			function($scope, $element, $attrs, $parse) {

				$scope.json = $attrs.json;
				if($scope.json === 'null' || $scope.json === '' || $scope.json === undefined) $scope.json = '[]';

				$scope.collection = angular.fromJson($scope.json);

				var index = $scope.collection.length;
				$scope.addItem = function(){
					$scope.collection.push('Field #'+index);
					index++;
				};
				$scope.removeItem = function(index){
					$scope.collection.splice(index, 1);
				};

				$scope.$watch('collection', function(val){
					$scope.json = angular.toJson(val);
				}, true);

			}
		]
	};
})

.directive('catalogVariants', function() {
	return {
		restrict: 'A',
		scope: true,
		controller: [
			'$scope', '$element', '$attrs', '$http',
			function($scope, $element, $attrs, $http) {

				$scope.variants = [];
				$scope.removed = [];

				function load() {
					$http({
						method: 'GET',
						url: $attrs.catalogVariants
					}).then(function(response) {
						if(response.data.status == 'success'){
							$scope.variants = response.data.data;
						}else if(response.data.status == 'error'){
							var message = response.data.message || 'An error has occurred!';
							toastr.error(message);
						}
						
					}, function(error) {
						toastr.error('Error!');
						console.log(error);
					});
				}

				load();

				$scope.add = function(){
					$scope.variants.push({});
				};

				$scope.remove = function(index){
					$scope.variants[index].removed = 1;
				};

				$scope.restore = function(index){
					$scope.variants[index].removed = 0;
				};

				$scope.save = function(){
					$http({
						method: 'POST',
						url: $attrs.updateUrl,
						data: $scope.variants
					}).then(function(response) {
						if(response.data.status == 'success'){
							var message = response.data.message || 'Saved!';
							toastr.success(message);
						}else if(response.data.status == 'error'){
							var message = response.data.message || 'An error has occurred!';
							toastr.error(message);
						}
						var updated = [];
						for (var i = 0; i < $scope.variants.length; i++) {
							if(!$scope.variants[i].removed){
								updated.push($scope.variants[i]);
							}
						}
						$scope.variants = updated;
						//$scope.$apply();
						
					}, function(error) {
						toastr.error('Error!');
						console.log(error);
					});
				};

			}
		]
	};
})

.directive('texttime', [function(dateFilter) {
	return {
		restrict: 'A',
		require: '?ngModel',
		link: function($scope, $element, $attrs, ngModel) {

			var format = $attrs.datetime;

			//в инпуте
			ngModel.$formatters.push(function(modelValue) {
				var d = new Date();
				if(modelValue){
					var date = modelValue.split('-');
					if(date.length > 2){
						return new Date(parseInt(date[0]), parseInt(date[1]) - 1, parseInt(date[2]));
					}
				}
				
			});

			//в ng-model
			ngModel.$parsers.push(function(d) {
				if(d instanceof Date){
					var date = [d.getFullYear(), d.getMonth() + 1, d.getDate()];
					return date.join('-');
				}
			});
		}
	}
}])


;