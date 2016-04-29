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
        $http({
          method: 'get',
          url: "http://asiliwar.idl.pl/rezerwacje/public/index.php/api/" + $scope.room_state + "/"+$filter('date')($scope.date_since,'yyyy-MM-ddTHH:mm:ss.sssZ', 'UTC')+"/"+$filter('date')($scope.date_till,'yyyy-MM-ddTHH:mm:ss.sssZ', 'UTC')
        }).success(function(response) {
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
        Room.remove({number: roomID}, function(success) {
           $("table").find("[data-id='" + roomID + "']").fadeOut();
        });
      }, null);
    };


  }]).controller('RoomCreationCtrl', ['$scope', '$location', 'Room',  function ($scope, $location, Room) {
    $scope.room = new Room();
    
    $scope.saveNew = function() {
      Room.save($scope.room);
      $location.path('/rooms/');
    };

    
  }]).controller('RoomEditionCtrl', ['$scope', '$routeParams', '$location', 'Room', function ($scope, $routeParams, $location, Room) {
    $scope.room = Room.get({number: $routeParams.id});
    
    $scope.saveChanges = function() {
      Room.update({number: $scope.room.number}, $scope.room);
      $location.path('/rooms/');
    };
  }]);
