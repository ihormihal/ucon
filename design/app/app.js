angular.module('app', ['im-dataTable', 'im-imgUpload', 'im-progress', 'im-autocomplete'])

.config([function () {

}])

.run([function (){

}])

.controller('mainController', ['$scope', function($scope){

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

				var input = $element[0].getElementsByTagName('input')[0];

				var json = $attrs.json;
				if(json === 'null' || json === '' || json === undefined) json = '[]';

				$scope.collection = angular.fromJson(json);

				var index = $scope.collection.length;
				$scope.addItem = function(){
					$scope.collection.push('Field #'+index);
					index++;
				};
				$scope.removeItem = function(index){
					$scope.collection.splice(index, 1);
				};

				$scope.$watch('collection', function(val){
					input.value = angular.toJson(val);
				}, true);

			}
		]
	};
})



;