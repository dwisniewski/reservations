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
  	$scope.eventSources = [];

  	$scope.uiConfig = {
      calendar:{
        editable: true,
        selectable: true,
        lang: "pl",
        header:{
          center: 'Wybierz datę z listy',
          right: 'today prev,next'
        },
        //dayNames: ['Niedziela', 'Poniedziałek', 'Wtorek', 'Środa', 'Czwartek', 'Piątek', 'Sobota'],
        //dayNamesShort: ['Nie', 'Pon', 'Wt', 'Śr', 'Czw', 'Pią', 'Sob'],
        dayClick: function() { console.log('text'); return false; },
        eventDrop: $scope.alertOnDrop,
        eventResize: $scope.alertOnResize
      }
    };

  }]);
