'use strict';

/**
 * @ngdoc function
 * @name frontendApp.controller:RoomsCtrl
 * @description
 * # RoomsCtrl
 * Controller of the frontendApp
 */
angular.module('frontendApp')
  .controller('RoomsCtrl', ['$http', '$filter', '$scope', '$location', 'Room', 'modalTimeService', function ($http, $filter, $scope, $location, Room, modalTimeService) {

  	$scope.all_rooms = Room.query();

    $scope.$watch(function() {
      return $scope.date_since;
    }, function(newTime) {
      if($scope.date_till !== undefined) {
        console.log('xxx');
        $http({
          method: 'get',
          url: "/api/rooms_free/"+$filter('date')($scope.date_since,'yyyy-MM-dd HH:mm:ss')+"/"+$filter('date')($scope.date_till,'yyyy-MM-dd HH:mm:ss')+"/"
        }).success(function(response) {
          console.log("/api/rooms_free/"+$filter('date')($scope.date_since,'yyyy-MM-dd HH:mm:ss')+"/"+$filter('date')($scope.date_till,'yyyy-MM-dd HH:mm:ss')+"/");
          console.log('success');
          console.log(response);
          $scope.all_rooms = response;
        });
      }
    });
    
  //  $scope.$watch(function() {
  //          return $scope.date_since;
  //        }, function(newTime) {
   //         if($scope.date_till != undefined) {
              /*$http({
                method: 'get',
                url: '/api/rooms_free/{{$scope.date_since}}/{{$scope.date_till}}/',
              }).success(function (response) {
                console.log(response);
                $scope.all_rooms = response;
              }).error(function (err) {
                console.log(err);
              });*/

              //$scope.all_rooms = 
   //         }
  //        });


 //   $scope.$watch(function() {
   //         return $scope.date_till;
     //     }, function(newTime) {
            
         //   if($scope.date_since !== undefined) {
       //       if($scope.date_till != undefined) {
                /*$http({
                  method: 'get',
                  url: '/api/rooms_free/{{$scope.date_since}}/{{$scope.date_till}}/',
                }).success(function (response) {
                  console.log(response);
                  $scope.all_rooms = response;
                }).error(function (err) {
                  console.log(err);
                });*/
        //    } 
        //  });
  	/*$scope.eventSources = [];

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
