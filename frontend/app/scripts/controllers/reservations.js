'use strict';

/**
 * @ngdoc function
 * @name frontendApp.controller:ReservationsCtrl
 * @description
 * # ReservationsCtrl
 * Controller of the frontendApp
 */
angular.module('frontendApp')
  .controller('ReservationsCtrl', ['$scope', '$location', 'Reservation', 'modalService', function ($scope, $location, Reservation, modalService) {

    this.awesomeThings = [
      'HTML5 Boilerplate',
      'AngularJS',
      'Karma'
    ];

    $scope.future_reservations = Reservation.query();

    $scope.createReservation = function() {
      $location.path('/reservation-create/');
    };

    $scope.editReservation = function(reservationID) {
      $location.path('/reservation-edit/' + reservationID);
    };

    $scope.deleteReservation = function(reservationID) {
      var modalOptions = {
            closeButtonText: 'Nie usuwaj',
            actionButtonText: 'Usuń rezerwację',
            headerText: 'Czy usunąć rezerwację?',
            bodyText: 'Czy na pewno chcesz usunąć rezerwację?'
        };

        modalService.showModal({}, modalOptions).then(function (result) {
          console.log('deleting' + reservationID);
        Reservation.remove({id: reservationID}, function(success) {
        $("table").find("[data-id='" + reservationID + "']").fadeOut();
        });
        }, null);
    };

    /*$scope.future_reservations = [{
    	'locator': 'test',
    	'since': 'today',
    	'till': 'tomorrow',
    	'head_count': 1,
    	'reservation_time': 'now'
    }];*/
  }]).controller('ReservationCreationCtrl', ['$http', '$filter', '$scope', '$location', 'Room', 'Reservation', 'Locator',  function ($http, $filter, $scope, $location, Room, Reservation, Locator) {
    $scope.reservation = new Reservation();
    
    $scope.locators = Locator.query(function() {
    $scope.locatorsIDName = [];
    $scope.paidOptions = [{"id": 1, "text": "Tak"}, {"id": 0, "text": "Nie"}];
      
      for(var i=0; i<$scope.locators.length; i++){
        $scope.locatorsIDName.push({
          "name_surname": $scope.locators[i].surname + " " + $scope.locators[i].name,
          "id": $scope.locators[i].id
        });
      }
    });


    function update_roomlist() {
      if($scope.reservation.since !== undefined && $scope.reservation.till !== undefined) {
        $('#rooms_view').removeClass('hidden');
        
        $http({
          method: 'get',
          url: "/api/rooms_free/"+$filter('date')($scope.reservation.since,'yyyy-MM-dd HH:mm:ss')+"/"+$filter('date')($scope.reservation.till,'yyyy-MM-dd HH:mm:ss')+"/"
        }).success(function(response) {
          $scope.available_rooms = response;
          $scope.chosen_rooms = [];
        });
      }      
    }

    $scope.assignRoom = function(number) {
      for(var i=0; i<$scope.available_rooms.length; i++) {

        if($scope.available_rooms[i].number == number) {
          console.log("Found room: " + number);
          var roomsArray = $scope.available_rooms.splice(i, 1);
          $scope.chosen_rooms = $scope.chosen_rooms.concat(roomsArray);
          return;
        }
      }
    }

    $scope.unassignRoom = function(number) {
      console.log("Unassign number: " + number);
      for(var i=0; i<$scope.chosen_rooms.length; i++) {
        //console.log(number + " " + $scope.chosen_rooms[i].number);
        if($scope.chosen_rooms[i].number == number) {
          var roomsArray = $scope.chosen_rooms.splice(i, 1);
          $scope.available_rooms = $scope.available_rooms.concat(roomsArray);
          return;
        }
      }
    }

    $scope.$watch(function() {
      return $scope.reservation.since;
    }, function(newTime) {
      update_roomlist();
    });

    $scope.$watch(function() {
      return $scope.reservation.till;
    }, function(newTime) {
      update_roomlist();
    });

    $scope.saveNew = function() {
      $scope.reservation.rooms = [];
      for(var i=0; i<$scope.chosen_rooms.length; i++) {
        $scope.reservation.rooms.push($scope.chosen_rooms[i].number);
      }
      Reservation.save($scope.reservation);
      $location.path('/reservations/');
    };

    $scope.redirectMainPage = function() {
      $location.path('/reservations/');
    };
  }])

  .controller('ReservationEditionCtrl', ['$http', '$scope',  '$filter',  '$routeParams', '$location', 'Room', 'Locator', 'Reservation', function ($http, $scope, $filter, $routeParams, $location, Room, Locator, Reservation) {
    
    $scope.paidOptions = [{"id": 1, "text": "Tak"}, {"id": 0, "text": "Nie"}];
    $scope.reservation = Reservation.get({id: $routeParams.id}, function() {
    $scope.available_rooms = []
    $scope.chosen_rooms = [];
    $scope.reservation.since = new Date($scope.reservation.since);
    $scope.reservation.till = new Date($scope.reservation.till);

    $('#rooms_view').removeClass('hidden');
     
      if($scope.reservation.since !== undefined && $scope.reservation.till !== undefined) {
        
        $('#rooms_view').removeClass('hidden');
        
        $http({
          method: 'get',
          url: "/api/rooms_free/"+$filter('date')($scope.reservation.since,'yyyy-MM-dd HH:mm:ss')+"/"+$filter('date')($scope.reservation.till,'yyyy-MM-dd HH:mm:ss')+"/"
        }).success(function(response) {
          $scope.available_rooms = response;
          $scope.chosen_rooms = [];
        });

        $http({
          method: 'get',
          url: "/api/room/"
        }).success(function(response) {
          var rooms_in_building = response;
        
          for(var i=0; i<rooms_in_building.length; i++) {
            for(var j=0; j<$scope.reservation.rooms.length; j++) {
              if(rooms_in_building[i].number == $scope.reservation.rooms[j]) {
                $scope.chosen_rooms.push(rooms_in_building[i]);
                continue;
              }
            }
          }
        });
      }      
    });

    $scope.locators = Locator.query(function() {
    $scope.locatorsIDName = [];
      
      for(var i=0; i<$scope.locators.length; i++){
        $scope.locatorsIDName.push({
          "name_surname": $scope.locators[i].surname + " " + $scope.locators[i].name,
          "id": $scope.locators[i].id
        });
      }
    });
    

    $scope.assignRoom = function(number) {
      for(var i=0; i<$scope.available_rooms.length; i++) {
          if($scope.available_rooms[i].number == number) {
            var roomsArray = $scope.available_rooms.splice(i, 1);
            $scope.chosen_rooms = $scope.chosen_rooms.concat(roomsArray);
            return;
          }
        }
    }

    $scope.unassignRoom = function(number) {
      console.log("Unassign number: " + number);
      for(var i=0; i<$scope.chosen_rooms.length; i++) {
        //console.log(number + " " + $scope.chosen_rooms[i].number);
        if($scope.chosen_rooms[i].number == number) {
          var roomsArray = $scope.chosen_rooms.splice(i, 1);
          $scope.available_rooms = $scope.available_rooms.concat(roomsArray);
          return;
        }
      }
    }

    $scope.saveChanges = function() {
      $scope.reservation.rooms = [];
      
      for(var i=0; i<$scope.chosen_rooms.length; i++) {
        $scope.reservation.rooms.push($scope.chosen_rooms[i].number);
      }
      
      Reservation.update({id: $scope.reservation.id}, $scope.reservation);
      $location.path('/reservations/');
    };

    $scope.redirectMainPage = function() {
      $location.path('/reservations/');
    };
  }]);
