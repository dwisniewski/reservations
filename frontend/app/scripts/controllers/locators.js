'use strict';

/**
 * @ngdoc function
 * @name frontendApp.controller:ReservationsCtrl
 * @description
 * # ReservationsCtrl
 * Controller of the frontendApp
 */
angular.module('frontendApp')
  .controller('LocatorsCtrl', ['$scope', 'Locator', 'LocatorByToken', function ($scope, Locator, LocatorByToken) {
    $scope.all_locators = Locator.query();

    $scope.search = {}
    $scope.search.keyUp = function() {
    	if($scope.search.input && $scope.search.input.length > 0) {
	    	LocatorByToken.query({'token': $scope.search.input}, function(data) {
	    		$scope.all_locators = data;
	    	});
		} else {
			$scope.all_locators = Locator.query();
		}
    }
  }]);
