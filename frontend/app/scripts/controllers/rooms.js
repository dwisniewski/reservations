'use strict';

/**
 * @ngdoc function
 * @name frontendApp.controller:RoomsCtrl
 * @description
 * # RoomsCtrl
 * Controller of the frontendApp
 */
angular.module('frontendApp')
  .controller('RoomsCtrl', ['$http', '$filter', '$scope', '$location', 'Room', function ($http, $filter, $scope, $location, Room) {

    $scope.all_rooms = Room.query();

    function update_roomlist() {
      if($scope.date_since !== undefined && $scope.date_till !== undefined) {
        //console.log('xxx');
        $http({
          method: 'get',
          url: "/api/" + $scope.room_state + "/"+$filter('date')($scope.date_since,'yyyy-MM-dd HH:mm:ss')+"/"+$filter('date')($scope.date_till,'yyyy-MM-dd HH:mm:ss')+"/"
        }).success(function(response) {
          /*console.log("/api/rooms_free/"+$filter('date')($scope.date_since,'yyyy-MM-dd HH:mm:ss')+"/"+$filter('date')($scope.date_till,'yyyy-MM-dd HH:mm:ss')+"/");*/
          console.log("/api/" + $scope.room_state + "/"+$filter('date')($scope.date_since,'yyyy-MM-dd HH:mm:ss')+"/"+$filter('date')($scope.date_till,'yyyy-MM-dd HH:mm:ss')+"/");
          $scope.all_rooms = response;
        });
      }      
    }


    $scope.$watch(function() {
      return $scope.date_since;
    }, function(newTime) {
      update_roomlist();
    });

    $scope.$watch(function() {
      return $scope.date_till;
    }, function(newTime) {
      update_roomlist();
    });

    $scope.$watch(function() {
      return $scope.room_state;
    }, function(room_state) {
      update_roomlist();
    });

/*
    var modalOptions = {
          closeButtonText: 'Odrzuć wybór',
          actionButtonText: 'Użyj przedziału czasu',
          headerText: 'Wybór godziny',
          bodyText: 'Podaj godzinę początkową i końcową dla wybranych dni'
      };
  */
  /*	$scope.uiConfig = {
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
        dayClick: function(ev) {       
          console.log(ev);
          modalTimeService.showModal({}, modalOptions).then(function (result) {
            console.log('deleting' + userID);
            
      }, null); return false; },
        eventDrop: $scope.alertOnDrop,
        eventResize: $scope.alertOnResize
      }
    };*/

  }]);
