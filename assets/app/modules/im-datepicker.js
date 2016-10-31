/*
 * Angular - Directive "im-datepicker"
 * im-datepicker - v0.0.1 - 2016-10-23
 * https://github.com/ihormihal/IM-Framework
 * datepicker.php
 * Ihor Mykhalchenko (http://mycode.in.ua/)
 */
 

angular.module('im-datepicker', [])

.directive('imDatepicker', function() {
	return {
		restrict: 'E',
		scope: {
			inpopup: '@'
		},
		template: '<ng-transclude></ng-transclude>'+
			'<div class="im-datepicker" ng-class="{\'inpopup\': inpopup, \'visible\': datepicker.visible}">'+
				'<div class="im-datepicker-controls">'+
					'<div class="control-month">'+
						'<div class="month">{{months[datepicker.date.month]}} <span class="year" ng-click="toggleYears()" ng-class="{\'active\': yearSelection}">{{datepicker.date.year}}</span></div>'+
						'<div class="prev" ng-click="prevMonth()"><span><i class="fa fa-angle-left"></i></span></div>'+
						'<div class="next" ng-click="nextMonth()"><span><i class="fa fa-angle-right"></i></span></div>'+
					'</div>'+
				'</div>'+
				'<table class="im-datepicker-years" ng-show="yearSelection">'+
					'<tbody>'+
						'<tr>'+
							'<td ng-repeat="year in years_line1" ng-click="pickYear(year)" ng-class="{\'current\': year.current, \'selected\': year.selected}"><span>{{year.year}}</span></td>'+
						'</tr>'+
						'<tr>'+
							'<td ng-repeat="year in years_line2" ng-click="pickYear(year)" ng-class="{\'current\': year.current, \'selected\': year.selected}"><span>{{year.year}}</span></td>'+
						'</tr>'+
					'</tbody>'+
				'</table>'+
				'<div class="im-datepicker-controls" ng-show="yearSelection">'+
					'<div class="control-years">'+
						'<div class="prev" ng-click="prevYears()"><span><i class="fa fa-angle-left"></i></span></div>'+
						'<div class="next" ng-click="nextYears()"><span><i class="fa fa-angle-right"></i></span></div>'+
					'</div>'+
				'</div>'+
				'<table class="im-datepicker-table">'+
					'<thead>'+
						'<tr>'+
							'<th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th><th>Sun</th>'+
						'</tr>'+
					'</thead>'+
					'<tr ng-repeat="week in datepicker.page">'+
						'<td class="day" ng-repeat="day in week" ng-click="pickDate(day)" ng-class="{\'current\': day.current, \'selected\': day.selected, \'not-current\': !day.visible}">{{day.day}}</td>'+
					'</tr>'+
				'</table>'+
			'</div>',
		transclude: true,
		link: function($scope, $element, $attrs, $ctrl, $transclude) {

			var config = {
				weeks: 6, //visible weeks
				month: [
					31, //January
					28, //February
					31,	//Marth
					30,	//April
					31,	//May
					30, //June
					31, //July
					31, //August
					30, //September
					31, //October
					30, //November
					31  //December
				],
				monthNames: [
					'January',
					'February',
					'Marth',
					'April',
					'May',
					'June',
					'July',
					'August',
					'September',
					'October',
					'November',
					'December'
				]
			};

			$scope.months = config.monthNames;

			var input = $element[0].getElementsByTagName('input')[0];

			$scope.current =  new Date();
			$scope.datepicker = {
				visible: true,
				current: {
					day: $scope.current.getDate(),
					month: $scope.current.getMonth(),
					year: $scope.current.getFullYear()
				},
				date: {
					day: 19,
					month: 9,
					year: 2016
				},
				page: []
			};

			function init() {
				if(input.value){
					var val = input.value.split('.');
					$scope.datepicker.date = {
						day: parseInt(val[0]),
						month: parseInt(val[1]) - 1,
						year: parseInt(val[2])
					};
				}
			}
			init();
			input.onchange = function(event){
				init();
				$scope.$apply();
			};

			document.onclick = function(event){
				var outerClick = true;
				for (var i = 0; i < event.path.length; i++) {
					if(event.path[i].nodeName == 'IM-DATEPICKER'){
						outerClick = false;
					}
				}
				if(outerClick){
					$scope.datepicker.visible = false;
					$scope.$apply();
				}
			};

			var hideDelay;
			var hidePopup = function(){
				$scope.datepicker.visible = false;
				$scope.$apply();
			};
			if($scope.inpopup){
				$scope.datepicker.visible = false;
				input.onfocus = function(){
					$scope.datepicker.visible = true;
					$scope.$apply();
				};
				input.onblur = function(){
					hideDelay = setTimeout(hidePopup, 100);
				};
			}


			function daysInMonth(year, month){
				if(month < 0) month = 11;
				if(month > 11) month = 0;
				if(year%4 == 0 && month == 1){
					return 29;
				}else{
					return config.month[month];
				}
			}

			$scope.pickDate = function(date){
				clearTimeout(hideDelay);
				$scope.datepicker.date.year = date.year;
				$scope.datepicker.date.month = date.month;
				$scope.datepicker.date.day = date.day;
				$scope.datepicker.visible = false; //close after
			};

			$scope.pickYear = function(year){
				clearTimeout(hideDelay);
				$scope.datepicker.date.year = year.year;
			};

			$scope.prevMonth = function(){
				clearTimeout(hideDelay);
				$scope.datepicker.date.month--;
			};

			$scope.nextMonth = function(){
				clearTimeout(hideDelay);
				$scope.datepicker.date.month++;
			};

			$scope.prevYears = function(){
				clearTimeout(hideDelay);
				$scope.datepicker.date.year-= 10;
			};

			$scope.nextYears = function(){
				clearTimeout(hideDelay);
				$scope.datepicker.date.year+= 10;
			};

			$scope.yearSelection = false;
			$scope.toggleYears = function(){
				clearTimeout(hideDelay);
				$scope.yearSelection = !$scope.yearSelection;
			};
			

			$scope.$watch('datepicker.date', function(val){

				if(val.month < 0){
					$scope.datepicker.date.year--;
					$scope.datepicker.date.month = 11;
				}

				if(val.month > 11){
					$scope.datepicker.date.year++;
					$scope.datepicker.date.month = 0;
				}

				if(val.day < 1){
					$scope.datepicker.date.month--;
					var days = daysInMonth(val.year, val.month);
					$scope.datepicker.date.day = days;
				}

				if(val.day > daysInMonth(val.year, val.month)){
					$scope.datepicker.date.month++;
					$scope.datepicker.date.day = 1;
				}


				var d = new Date(val.year, val.month, val.day);

				
				//calc offset
				var dayIndexOfWeek = d.getDay() == 0 ? 6 : d.getDay() - 1;
				var offset = dayIndexOfWeek + 1 - d.getDate()%7;
				if(offset < 0) offset = 7 + offset;
				if(offset >= 7) offset = 7 - offset;


				$scope.datepicker.page = [];
				var week = [];
				var daysInCurrent = daysInMonth(d.getFullYear(), d.getMonth());
				var daysInPrev = daysInMonth(d.getFullYear(), d.getMonth() - 1);

				for(var i = 1; i <= config.weeks*7; i++){
					var day = {
						day: i - offset,
						month: d.getMonth(),
						year: d.getFullYear(),
						visible: true,
						current: false
					};

				
					if(day.day > daysInCurrent){
						//fill next month week
						day.visible = false;
						day.day = day.day - daysInCurrent;
						if(day.month == 11){
							day.month = 0;
							day.year++;
						}else{
							day.month++;
						}
					}else if(day.day < 1){
						//fill prev month week
						day.visible = false;
						day.day = daysInPrev + day.day;
						if(day.month == 0){
							day.month = 11;
							day.year--;
						}else{
							day.month--;
						}
					}

					//set selected label
					if(day.day == d.getDate() && day.month == d.getMonth()){
						day.selected = true;
					}

					//set current label
					if(
						day.day == $scope.datepicker.current.day && 
						day.month == $scope.datepicker.current.month &&
						day.year == $scope.datepicker.current.year
					){
						day.current = true;
					}

					week.push(day);
					if(i%7 == 0){
						$scope.datepicker.page.push(week);
						week = [];
					}
				}

				//years selection
				var startYear = $scope.datepicker.date.year - $scope.datepicker.date.year%10;
				$scope.years_line1 = [];
				$scope.years_line2 = [];
				for (var i = startYear; i <= startYear+4; i++) {
					$scope.years_line1.push({
						year: i,
						current: $scope.datepicker.current.year == i,
						selected: d.getFullYear() == i
					});
				}
				for (var i = startYear+5; i <= startYear+9; i++) {
					$scope.years_line2.push({
						year: i,
						current: $scope.datepicker.current.year == i,
						selected: d.getFullYear() == i
					});
				}

				//output
				var inputValues = {
					day: (val.day < 10) ? ('0' + val.day) : val.day,
					month: (val.month+1) < 10 ? '0' + (val.month+1) : (val.month+1),
					year: val.year
				};

				input.value = inputValues.day + '.' + inputValues.month + '.' + inputValues.year;				


			}, true);
			
		}
	}
});