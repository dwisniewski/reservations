'use strict';

/**
 * @ngdoc function
 * @name frontendApp.controller:ReservationsCtrl
 * @description
 * # ReservationsCtrl
 * Controller of the frontendApp
 */
angular.module('frontendApp')
  .controller('ReservationsCtrl', ['$scope', 'Reservation', function ($scope, Reservation) {

    this.awesomeThings = [
      'HTML5 Boilerplate',
      'AngularJS',
      'Karma'
    ];


    /*$scope.future_reservations = [{
    	'locator': 'test',
    	'since': 'today',
    	'till': 'tomorrow',
    	'head_count': 1,
    	'reservation_time': 'now'
    }];*/
    $scope.future_reservations = Reservation.query();
  }]);
