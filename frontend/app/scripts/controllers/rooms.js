'use strict';

/**
 * @ngdoc function
 * @name frontendApp.controller:RoomsCtrl
 * @description
 * # RoomsCtrl
 * Controller of the frontendApp
 */
angular.module('frontendApp')
  .controller('RoomsFindCtrl', ['$http', '$filter', '$scope', '$location', 'Room', function ($http, $filter, $scope, $location, Room) {

    $scope.all_rooms = Room.query();

    $scope.manageRooms = function() {
      $location.path('/rooms/');
    };

    $scope.visitRoom = function(number) {
      $location.path('/room-edit/'+number);
    }


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

  }]).controller('RoomsCtrl', ['$scope', '$location', 'Room', 'modalService',  function ($scope, $location, Room, modalService) {
    $scope.found_rooms = Room.query();

    $scope.createRoom = function() {
      $location.path('/room-create/');
    };

    $scope.editRoom = function(roomID) {
      $location.path('/room-edit/' + roomID);
    };

    $scope.deleteRoom = function(roomID) {
      var modalOptions = {
            closeButtonText: 'Nie usuwaj',
            actionButtonText: 'Usuń pokój',
            headerText: 'Czy usunąć pokój?',
            bodyText: 'Czy na pewno chcesz usunąć wybrany pokój?'
        };

        modalService.showModal({}, modalOptions).then(function (result) {
        console.log('deleting' + roomID);
        Room.remove({number: roomID}, function(success) {
        $("table").find("[data-id='" + roomID + "']").fadeOut();
        });
        }, null);
    };


  }]).controller('RoomCreationCtrl', ['$scope', '$location', 'Room',  function ($scope, $location, Room) {
    $scope.room = new Room();
    
    $scope.saveNew = function() {
      console.log($scope.room);
      Room.save($scope.room);
      $location.path('/rooms/');
    };
  }])

  .controller('RoomEditionCtrl', ['$scope', '$routeParams', '$location', 'Room', function ($scope, $routeParams, $location, Room) {
    $scope.room = Room.get({number: $routeParams.id});
    
    $scope.saveChanges = function() {
      Room.update({number: $scope.room.number}, $scope.room);
      console.log($scope.room);
      $location.path('/rooms/');
    };
  }]);
