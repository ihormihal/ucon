angular.module('im-progress', [])

.directive('imProgress', [function() {
	return {
		restrict: 'E',
		scope: {
			active: '@',
			progress: '@',
		},
		template: '<svg class="progress-bar" ng-class="{\'complete\' : left == 0}" version="1.1" xmlns="http://www.w3.org/2000/svg">' +
			'<circle class="bar" fill="transparent" style="stroke-dashoffset: {{offset}}px; stroke: rgb({{color.r}},{{color.g}},{{color.b}});"></circle>' +
			'<text class="text" alignment-baseline="middle" text-anchor="middle" x="50%" y="50%" style="fill: rgb({{color.r}},{{color.g}},{{color.b}});">{{progress}}%</text>'+
		'</svg>',
		controller: function($scope, $element, $attrs) {

			var color_from = {
				r: 255,
				g: 87,
				b: 34
			};

			var color_to = {
				r: 65,
				g: 170,
				b: 0
			};


			$scope.color = color_from;

			var circleRadius = parseInt($attrs.radius);
			var stokeWidth = parseInt($attrs.width);
			var circleCenter = circleRadius + stokeWidth/2;
			var viewSize = circleCenter * 2;

			var dashLength = 2 * Math.PI * circleRadius;
			var dashOffset = dashLength / 4;
			//dashOffset = 0;

			$scope.offset = dashLength; //zero position
			var svg = $element[0].getElementsByTagName('svg')[0];
			var progressBar = $element[0].getElementsByTagName('circle')[0];
			var text = $element[0].getElementsByTagName('text')[0];

			svg.setAttribute('width', viewSize);
			svg.setAttribute('height', viewSize);
			svg.setAttribute('viewBox', '0 0 '+viewSize+' '+viewSize);

			progressBar.setAttribute('cx', circleCenter);
			progressBar.setAttribute('cy', circleCenter);
			progressBar.setAttribute('transform', 'rotate(-90 '+circleCenter+' '+circleCenter+')');

			progressBar.setAttribute('stroke-dasharray', dashLength);
			progressBar.setAttribute('r', circleRadius);
			progressBar.setAttribute('stroke-width', stokeWidth);

			text.setAttribute('font-size', circleRadius*0.5);



			var color_step = {
				r: Math.ceil((color_to.r - color_from.r)/100),
				g: Math.ceil((color_to.g - color_from.g)/100),
				b: Math.ceil((color_to.b - color_from.b)/100)
			};

			$scope.$watch('progress', function(){

				var RGBcolor = {
					r: 255,
					g: 255,
					b: 255
				};
				for(var color in RGBcolor){
					RGBcolor[color] = color_from[color] + color_step[color] * $scope.progress
					if(RGBcolor[color] < 0) RGBcolor[color] = 0;
					if(RGBcolor[color] >= 255) RGBcolor[color] = 255;
				}

				if($scope.progress == 100){
					RGBcolor = color_to;
				}

				$scope.color = RGBcolor;
				$scope.offset = (1 - ($scope.progress / 100)) * dashLength;

			});
		}
	}
}])

.directive('timer', [function() {
	return {
		restrict: 'E',
		scope: {
			active: '@',
			time: '@',
			left: '@',
			onEnd: '&'
		},
		template: '<svg class="progress-bar" ng-class="{\'complete\' : left == 0}" version="1.1" xmlns="http://www.w3.org/2000/svg"><circle class="bar" fill="transparent" style="stroke-dashoffset: {{offset}}px; stroke: rgb({{color.r}},{{color.g}},{{color.b}});"></circle><text class="text" alignment-baseline="middle" text-anchor="middle" x="50%" y="50%" style="fill: rgb({{color.r}},{{color.g}},{{color.b}});">{{left}}</text></svg>',
		controller: function($scope, $element, $attrs) {

			var color_from = {
				r: 81,
				g: 177,
				b: 90
			};
			var color_to = {
				r: 255,
				g: 87,
				b: 34
			};

			$scope.color = color_from;

			var circleRadius = parseInt($attrs.radius);
			var stokeWidth = parseInt($attrs.width);
			var circleCenter = circleRadius + stokeWidth/2;
			var viewSize = circleCenter * 2;

			var dashLength = 2 * Math.PI * circleRadius;
			var dashOffset = dashLength / 4;
			//dashOffset = 0;

			$scope.offset = dashLength; //zero position
			var svg = $element[0].getElementsByTagName('svg')[0];
			var progressBar = $element[0].getElementsByTagName('circle')[0];
			var text = $element[0].getElementsByTagName('text')[0];

			svg.setAttribute('width', viewSize);
			svg.setAttribute('height', viewSize);
			svg.setAttribute('viewBox', '0 0 '+viewSize+' '+viewSize);

			progressBar.setAttribute('cx', circleCenter);
			progressBar.setAttribute('cy', circleCenter);
			progressBar.setAttribute('transform', 'rotate(-90 '+circleCenter+' '+circleCenter+')');

			progressBar.setAttribute('stroke-dasharray', dashLength);
			progressBar.setAttribute('r', circleRadius);
			progressBar.setAttribute('stroke-width', stokeWidth);

			text.setAttribute('font-size', circleRadius);


			var interval = null;
			function timerStart(){
				clearInterval(interval);
				if($scope.left > 0 && $scope.active == '1'){
					$scope.offset = ($scope.left / $scope.time) * dashLength;
					interval = setInterval(ticTac, 1000);
				}
			}

			$scope.$watch('active', function () {
				timerStart();
			});

			var color_step = {
				r: Math.ceil((color_to.r - color_from.r)/$scope.time),
				g: Math.ceil((color_to.g - color_from.g)/$scope.time),
				b: Math.ceil((color_to.b - color_from.b)/$scope.time)
			};

			function ticTac () {
				$scope.left--;
				if($scope.left == 0){
					clearInterval(interval);
					$scope.onEnd();
				}

				var RGBcolor = {
					r: 255,
					g: 255,
					b: 255
				};
				for(var color in RGBcolor){
					RGBcolor[color] = color_from[color] + color_step[color] * ($scope.time - $scope.left)
					if(RGBcolor[color] < 0) RGBcolor[color] = 0;
					if(RGBcolor[color] >= 255) RGBcolor[color] = 255;
				}

				$scope.color = RGBcolor;


				$scope.offset = ($scope.left / $scope.time) * dashLength;
				$scope.$apply();
			};


		}
	}
}])

;