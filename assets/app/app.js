angular.module('app', ['im-dataTable', 'im-imgUpload', 'im-progress', 'im-autocomplete'])

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



;