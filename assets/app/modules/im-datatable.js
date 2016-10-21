/*
 * Angular - Directive "imDatatable"
 * im-datatable - v0.4.6 - 2016-10-21
 * https://github.com/ihormihal/IM-Framework
 * datatable.php
 * Ihor Mykhalchenko (http://mycode.in.ua/)
 */
 /*
 IE >= 10
 Chrome >= 8.0
 Opera >= 11.50
 Firefox >= 3.6
 Safari >= 5.1
 */

angular.module('im-dataTable', [])

.directive('imDatatable', function($compile, $sce) {
	return {
		restrict: 'A',
		scope: true,
		//html dom table
		link: function(scope, element, attrs) {
			if (!attrs.getUrl) {
				scope.data = []; //rows data
				scope.dataAttr = []; //rows-attr data

				//map row properties and get types
				var types = {};
				var formats = {};
				var thead = element[0].getElementsByTagName('thead')[0];
				angular.forEach(thead.getElementsByTagName('th'), function(th, index) {
					types[index] = th.getAttribute('data-type');
					formats[index] = th.getAttribute('data-format');
					th.setAttribute('data-sortby', 'key' + index);
				});

				//I dont want to use moment.js
				var getDateFromString = function(string, format) {
					format = format.toLowerCase();
					if (format.length !== 10) {
						console.log('invalid date format!');
						return new Date(string);
					}

					var re = /([^(d|m|y)])/g;
					var divider = re.exec(format)[0];
					var splitted_format = format.split(divider);
					var indexes = {
						day: format.indexOf('dd'),
						month: format.indexOf('mm'),
						year: format.indexOf('yyyy')
					};
					var dateString = string.substr(indexes.year, 4) + '-' + string.substr(indexes.month, 2) + '-' + string.substr(indexes.day, 2);
					if (dateString.length !== 10) {
						console.log('invalid date format!');
						return new Date(string);
					}
					return new Date(dateString);
				};

				//var temp = getDateFromString('04.02.2017','dd.mm.yyyy');
				//console.log(temp);

				//read data and replace tbody by angular-template
				var tbody = element[0].getElementsByTagName('tbody')[0];
				angular.forEach(tbody.getElementsByTagName('tr'), function(tr, i) {
					var row = {
						attrs: {},
						cells: {}
					};
					row.attrs.href = tr.getAttribute('data-href');
					row.attrs.class = tr.getAttribute('class');

					angular.forEach(tr.children, function(td, index) {
						var text = (td.innerText || td.textContent).trim();
						var html = td.innerHTML;
						var className = td.className || '';

						if(text){
							//parse by types
							switch (types[index]) {
								case 'numeric':
									text = parseFloat(text);
									break;
								case 'date':
									text = getDateFromString(text, formats[index]);
									break;
								default:
									break;
							}
						}

						row.cells['key' + index] = {
							text: text,
							html: $sce.trustAsHtml(html),
							class: className
						};
					});

					scope.data.push(row);

					if (i == tbody.getElementsByTagName('tr').length - 1) {
						tbody.className = 'loaded';
					}

				});
				tbody.innerHTML = '<tr class="{{row.attrs.class}}" ng-repeat="row in rows" ng-click="goHref(row.attrs.href)"><td ng-repeat="cell in row.cells" class="{{cell.class}}" ng-bind-html="cell.html"></td></tr>';
				$compile(element.contents())(scope);
			}
		},
		//json data table
		controller: [
			'$scope', '$element', '$attrs', '$parse', '$http', '$timeout', '$filter',
			function($scope, $element, $attrs, $parse, $http, $timeout, $filter) {

				$element[0].getElementsByTagName('tbody')[0].className = 'loaded';


				$scope.editConfig = {
					name: {
						type: 'text'
					},
					currency: {
						type: 'select',
						values: [
							'QQQ',
							'WWW',
							'EEE'
						]
					}
				};

				$element[0].getElementsByTagName('tbody')[0].addEventListener('click', function(e) {
				    if (e.target.tagName === 'TD') {
				    	var cell = e.target;
				    	var scope = angular.element(cell).scope();
				    	var prop = cell.dataset.edit;
				        if(!cell.classList.contains('editing')){
							cell.classList.add('editing');
							var formEl = null;
							if($scope.editConfig[prop] == 'text'){
								formEl = document.createElement('input');
								formEl.type = 'text';
								formEl.value = scope.row[prop];
								cell.innerHTML = '';
								cell.appendChild(formEl);

								cell.childNodes[0].onblur = function(){
									scope.row[prop] = this.value;
									cell.innerHTML = this.value;
									cell.classList.remove('editing');
									scope.$apply();
								};
							}
						}
				    }
				});



				$scope.data = [];
				$scope.rows = [];
				$scope.selected = [];

				$scope.ajax = true;
				$scope.serverSide = false;
				$scope.search = {};
				$scope.sort = null;
				$scope.table = {
					ajax: true,
					total: 0,
					pages: 0,
					page: 1,
					onpage: '10',
					pagination: []
				};

				if ($attrs.serverSide == "true") {
					$scope.serverSide = true;
				}

				$scope.goHref = function(href) {
					if (href) {
						window.location.href = href;
					}
				};

				//html-based datatable if attr "get-url" is not defined
				if (!$attrs.getUrl) {
					$scope.table.ajax = false;
					$scope.search = {
						cells: {},
						attrs: {}
					};
				}

				$scope.unique = function(array, property){
					var unique = {};
					for (var i = 0; i < $scope.data.length; i++) {
						if($scope.data[i].hasOwnProperty(property)){
							unique[$scope.data[i][property]] = null;
						}
					}
					return Object.keys(unique);
				};

				//pagination, sorting, searching
				var filter = function() {
					if ($scope.table.ajax) {
						$scope.search.$ = $scope.s;
					}
					$scope.rows = $filter('filter')($scope.data, $scope.search);
					$scope.rows = $filter('orderBy')($scope.rows, $scope.sort);

					var onpage = parseInt($scope.table.onpage);
					var offset = ($scope.table.page - 1) * onpage;
					$scope.table.pages = Math.ceil($scope.rows.length / onpage);
					if ($scope.table.pages !== 0 && $scope.table.page > $scope.table.pages) {
						$scope.table.page = $scope.table.pages;
					}
					$scope.rows = $scope.rows.slice(offset, offset + onpage);
				};

				//ajax data loading
				$scope.loadData = function() {
					$http({
							url: $attrs.getUrl,
							method: 'GET'
						})
						.then(function(response) {
							$scope.data = response.data;
							filter();
						}, function(error) {
							console.log(error);
						});
				};

				$scope.loadFilterData = function() {
					var params = '?onpage=' + $scope.table.onpage + '&page=' + $scope.table.page;

					if ($scope.sort) {
						var sortdir = $scope.sort.charAt(0) == '-' ? 'DESC' : 'ASC';
						var sortby = $scope.sort.replace('-', '');
						params += '&sortdir=' + sortdir;
						params += '&sortby=' + sortby;
					}
					if ($scope.s) {
						params += '&s=' + $scope.s;
					}

					if ($scope.search) {
						params += '&search=' + angular.toJson($scope.search);
					}

					$http({
							url: $attrs.getUrl + params,
							method: 'GET'
						})
						.then(function(response) {
							$scope.rows = response.data.rows;
							$scope.table.total = response.data.total;
							$scope.table.page = response.data.page;
							$scope.table.pages = response.data.pages;
						}, function(error) {
							console.log(error);
						});
				};

				//ajax update row
				$scope.updateRow = function(index) {
					var data = $scope.rows[index];
					$http({
							url: $attrs.updateUrl,
							method: 'POST',
							data: data
						})
						.then(function(response) {
							$scope.rows[index] = response.data;
							$scope.rows[index].class = 'success';
							$timeout(function() {
								$scope.rows[index].class = '';
							}, 1000);
						}, function(error) {
							console.log(error);
							$scope.rows[index].class = 'error';
							$timeout(function() {
								$scope.rows[index].class = '';
							}, 1000);
						});
				};

				//updateSelected
				$scope.updateSelected = function(options) {
					var toUpdate = [];
					for (var i = 0; i < $scope.selected.length; i++) {
						toUpdate[i] = {};
						angular.copy($scope.selected[i], toUpdate[i]);
						for (property in options) {
							toUpdate[i][property] = options[property];
						}
					}

					$http({
							url: $attrs.updateUrl,
							method: 'POST',
							data: toUpdate
						})
						.then(function(response) {
							//just update all table
							$scope.loadData();
						}, function(error) {
							console.log(error);
						});
				};


				//ajax loading for new rows
				$scope.watchRows = function() {
					$http({
							url: $attrs.watchUrl,
							method: 'GET'
						})
						.then(function(response) {
							var rows = response.data;
							for (var i = 0; i < rows.length; i++) {
								$scope.data.unshift(rows[i]);
							}
							if (!$scope.serverSide) {
								filter();
							}
						}, function(error) {
							console.log(error);
						});
				};

				if ($attrs.watchTime) {
					var watchInterval = parseFloat($attrs.watchTime) * 1000;
					if (watchInterval) {
						setInterval($scope.watchRows, watchInterval);
					}
				}

				//apply filter if any field is changed
				$scope.$watchGroup(['s', 'sort', 'table.page', 'table.onpage'], function(newVal, oldVal) {

					if($scope.serverSide){
						var newPages = Math.ceil($scope.table.total / parseInt(newVal[3]));
						//if current page number is too big
						if ($scope.table.page > newPages) {
							//choose last page
							$scope.table.page = newPages || 1;
							//and try again
							return false;
						}
					}

					if ($scope.serverSide) {
						$scope.loadFilterData();
					} else {
						filter();
					}

				});

				$scope.$watch('search', function() {
					if ($scope.serverSide) {
						$scope.loadFilterData();
					} else {
						filter();
					}
				}, true);

				//wach selected rows
				$scope.$watch('data', function() {
					for (var i = 0; i < $scope.data.length; i++) {
						$scope.data[i].class = $scope.data[i].selected ? 'selected' : '';
					}
					$scope.selected = $scope.data.filter(function(row) {
						return row.selected;
					});
				}, true);


				//sorting buttons
				var thead = $element[0].getElementsByTagName('thead')[0];
				angular.forEach(thead.getElementsByTagName('th'), function(th, index) {

					if (!$scope.table.ajax) {
						if (th.getAttribute('data-sortdir')) {
							var sby = 'cells.key' + index + '.text';
							var sdir = th.getAttribute('data-sortdir');
							$scope.sort = (sdir == 'ASC' ? '' : '-') + sby;
							filter();
						}
					}

					th.onclick = function() {

						if ($scope.table.ajax) {
							var sortby = th.getAttribute('data-sortby');
							if (sortby === null) {
								return false;
							}
						} else {
							var sortby = 'cells.key' + index + '.text';
						}

						//remove sortdir arrow in others
						angular.forEach($element[0].getElementsByTagName('th'), function(other) {
							if (th !== other) other.removeAttribute('data-sortdir');
						});

						var sortdir = th.getAttribute('data-sortdir');
						sortdir = sortdir == 'ASC' ? 'DESC' : 'ASC';
						th.setAttribute('data-sortdir', sortdir);


						$scope.sort = (sortdir == 'ASC' ? '' : '-') + sortby;
						$scope.$apply();
					};
				});

				if ($scope.table.ajax) {
					if ($scope.serverSide) {
						$scope.loadFilterData();
					} else {
						$scope.loadData();
					}
				} else {
					filter();
				}

			}
		]
	};
})

.directive('imDatatablePagination', function() {
	return {
		required: 'imDatatable',
		restrict: 'A',
		scope: false,
		template: '<ul class="pagination" ng-show="table.pages > 1">'+
			'<li ng-show="table.page > 1"><a ng-click="prev()"><i class="fa fa-angle-left"></i></a></li>'+
			'<li ng-repeat="page in table.pagination" ng-show="page.visible" ng-class="{active: page.active}">'+
				'<a ng-click="table.page = page.index">{{page.text}}</a>'+
			'</li>'+
			'<li ng-show="table.page < table.pages">'+
				'<a ng-click="next()"><i class="fa fa-angle-right"></i></a>'+
			'</li>'+
		'</ul>',
		controller: ['$scope', '$element', '$attrs', '$parse', function($scope, $element, $attrs, $parse) {

			$scope.prev = function() {
				$scope.table.page--;
			};

			$scope.next = function() {
				$scope.table.page++;
			};

			function buildPagination() {
				$scope.table.pagination = [];

				var fromPage = $scope.table.page - 5;
				var toPage = $scope.table.page + 5;
				if(fromPage < 0) fromPage = 0;

				for (var i = 1; i <= $scope.table.pages; i++) {
					var page = {
						index: i,
						text: i,
						visible: true,
						active: $scope.table.page == i
					};
					//pre
					if(i < fromPage && i !== 1){
						if(i == fromPage - 1){
							page.text = '...';
						}else{
							page.visible = false;
						}
					}
					//post
					if(i > toPage && i !== $scope.table.pages){
						if(i == toPage + 1){
							page.text = '...';
						}else{
							page.visible = false;
						}
					}

					$scope.table.pagination.push(page);
				}
			}

			$scope.$watch('table.page', function(){
				buildPagination();
			});
			$scope.$watch('table.pages', function(){
				buildPagination();
			});
		}]
	};
})

;
