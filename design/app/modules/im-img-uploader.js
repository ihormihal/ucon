/*
 * Angular - Directive "imDatatable"
 * im-datatable - v0.3.0 - 2016-10-12
 * https://github.com/ihormihal/IM-Framework
 * Ihor Mykhalchenko (http://mycode.in.ua/)
 */

angular.module('im-imgUpload', ['ngFileUpload'])

.directive('uploadImages', function() {
	return {
		restrict: 'E',
		scope: {
			name: '@',
			input: '@',
			title: '@',
			multiple: '@',
			locked: '@',
			updated: '=',
			output: '='
		},
		link: function($scope, $element, $attrs){
			$scope.contentUrl = $attrs.template;
			$attrs.$observe("template", function(template) {
				$scope.contentUrl = template;
			});
		},
		template: '<div ng-include="contentUrl"></div>',
		controller: [
			'$rootScope', '$scope', '$element', '$attrs', '$parse', '$timeout', '$http', 'Upload',
			function($rootScope, $scope, $element, $attrs, $parse, $timeout, $http, Upload) {

				$scope.serialize = function(obj, prefix) {
				    var str = [];
				    for (var p in obj) {
				        if (obj.hasOwnProperty(p)) {
				            var k = prefix ? prefix + "[" + p + "]" : p,
				                v = obj[p];
				            str.push(typeof v == "object" ?
				                $rootScope.serialize(v, k) :
				                encodeURIComponent(k) + "=" + encodeURIComponent(v));
				        }
				    }
				    return str.join("&");
				};


				$scope.files = [];
				$scope.output = [];
				$scope.updated = false;

				var multiple = false;
				if($attrs.multiple == 'true'){
					multiple = true;
					if($scope.input){
						$scope.output = angular.fromJson($scope.input);
						angular.forEach($scope.output, function(image) {
							$scope.output = $scope.input;
							$scope.files.push({id: image.id, src: image.src, thumb: image.thumb, loaded: true, loading: false, error: false, progress: 0});
						});
					}
				}else{
					multiple = false;
					if($scope.input){
						$scope.output[0] = $scope.input;
						$scope.files[0] = {src: $scope.input, loaded: true, loading: false, error: false, progress: 0};
					}
				}


				$scope.uploadFiles = function(files, errFiles) {
					$scope.errFiles = errFiles;

					angular.forEach(files, function(file) {
						if(!file) return false;
						$scope.files.push(file);
						file.upload = Upload.upload({
							method: 'POST',
							url: $attrs.url,
							headers: {
								'Content-Type': 'multipart/form-data'
							},
							file: file,
							data: {
								'Content-Type': file.type,
								image: file
							}
						});

						file.loading = true;
						file.upload.then(function(response) {
							var res = response.data;
							if(res.status == 'success'){
								file.id = res.data.id;
								file.src = res.data.src;
								file.thumb = res.data.thumb;
								file.loaded = true;
							}else{
								file.error = res.data.message;
								file.loading = false;
							}

							$scope.updateValue();


						}, function(response) {
							if (response.status > 0){
								$scope.error = response.status + ': ' + response.message;
							}
							file.loading = false;

						}, function(e) {
							if(e.total > 0){
								file.progress = parseInt(e.loaded*100/e.total);
							}else{
								file.progress = 0;
							}

						});
					});
				};

				$scope.updateValue = function(){

					$scope.output = [];
					angular.forEach($scope.files, function(file) {

						if(file.src){
							$scope.output.push(file.src);
						}
					});
					if(multiple){
						$scope.input = angular.toJson($scope.output);
					}else{
						if($scope.output[0]){
							$scope.input = $scope.output[0];
						}else{
							$scope.input = '';
						}
					}
					$scope.updated = true;

				};

				$scope.deleteImg = function(index) {
					$http({
						url: $attrs.delete,
						method: 'POST',
						data: $scope.serialize({id: $scope.files[index].id, src: $scope.files[index].src}),
						headers: {
							'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
						}
					})
					.then(function(response) {
						$scope.data = response.data;
						if(response.data.status == 'success'){
							toastr.success(response.data.message);
							$scope.files.splice(index, 1);
							$scope.updateValue();
						}else if(response.data.status == 'warning'){
							toastr.warning(response.data.message);
						}else if(response.data.status == 'error'){
							toastr.error(response.data.message);
						}
					}, function(error) {
						console.log(error);
					});
					//$scope.updateValue();
				};

			}
		]
	};
});