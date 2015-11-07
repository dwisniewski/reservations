'use strict';

/**
 * @ngdoc function
 * @name frontendApp.controller:RoomsCtrl
 * @description
 * # RoomsCtrl
 * Controller of the frontendApp
 */
angular.module('frontendApp')
  .controller('RoomsCtrl', ['$scope', '$location', 'Room', function ($scope, $location, Room) {

  	$scope.all_rooms = Room.query();
  	

  }]);
