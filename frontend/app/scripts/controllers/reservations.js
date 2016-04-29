'use strict';

/**
 * @ngdoc function
 * @name frontendApp.controller:ReservationsCtrl
 * @description
 * # ReservationsCtrl
 * Controller of the frontendApp
 */
angular.module('frontendApp')
  .controller('ReservationsCtrl', ['$scope', '$http', '$filter', '$location', 'Locator', 'Reservation', 'modalService', function ($scope, $http, $filter, $location, Locator, Reservation, modalService) {


    $scope.locators = Locator.query(function() {
    $scope.locatorsIDName = [];
    $scope.future_reservations = Reservation.query();
    $scope.filter_since = new Date(new Date().setHours(0,0,0,0));
      
      for(var i=0; i<$scope.locators.length; i++){
        $scope.locatorsIDName.push({
          "name_surname": $scope.locators[i].surname + " " + $scope.locators[i].name,
          "id": $scope.locators[i].id
        });
      }
    });



    function updateReservationsList() {
      $http({
          method: 'get',
          url: "http://asiliwar.idl.pl/rezerwacje/public/index.php/api/reservations-filtered/"+$scope.filter_locatorid+"/"+$filter('date')($scope.filter_since,'yyyy-MM-ddTHH:mm:ss.sssZ', 'UTC')+"/" + $filter('date')($scope.filter_till,'yyyy-MM-ddTHH:mm:ss.sssZ', 'UTC')
        }).success(function(response) {
          $scope.future_reservations = response;
        });
    };

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
        Reservation.remove({id: reservationID}, function(success) {
        $("table").find("[data-id='" + reservationID + "']").fadeOut();
        });
        }, null);
    };

    $scope.$watch(function() {
      return $scope.filter_locatorid;
    }, function(newTime) {
      updateReservationsList();
    });

    $scope.$watch(function() {
      return $scope.filter_since;
    }, function(newTime) {
      updateReservationsList();
    });
    
    $scope.$watch(function() {
      return $scope.filter_till;
    }, function(newTime) {
      updateReservationsList();
    });
    

  }]).controller('ReservationsForUserCtrl', ['$scope', '$http', '$location', '$routeParams', 'Locator', 'Reservation', 'modalService', function ($scope, $http, $location, $routeParams, Locator, Reservation, modalService) {

    var userID = $routeParams.userID;

    $http({
          method: 'get',
          url: "http://asiliwar.idl.pl/rezerwacje/public/index.php/api/reservation?for_locator=" + userID
        }).success(function(response) {
          $scope.future_reservations = response;
        });

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
        Reservation.remove({id: reservationID}, function(success) {
        $("table").find("[data-id='" + reservationID + "']").fadeOut();
        });
        }, null);
    };



  }]).controller('ReservationCreationCtrl', ['$http', '$filter', '$scope', '$location', 'Room', 'Reservation', 'Locator',  function ($http, $filter, $scope, $location, Room, Reservation, Locator) {
    $scope.reservation = new Reservation();
    $scope.reservation['is_paid'] = 0;
    $scope.reservation['people_count'] = 1;
    $scope.reservation['dinners_count'] = 1;
    
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
          url: "http://asiliwar.idl.pl/rezerwacje/public/index.php/api/rooms_free/"+$filter('date')($scope.reservation.since,'yyyy-MM-ddTHH:mm:ss.sssZ', 'UTC')+"/"+$filter('date')($scope.reservation.till,'yyyy-MM-ddTHH:mm:ss.sssZ', 'UTC')
        }).success(function(response) {
          $scope.available_rooms = response;
          $scope.chosen_rooms = [];
        });
      }      
    }

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
      for(var i=0; i<$scope.chosen_rooms.length; i++) {
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
      if($scope.chosen_rooms == undefined || $scope.chosen_rooms.length == 0) {
        alert('Wybierz co najmniej jeden pokój poprzez kliknięcie na wybrany pokój z listy dostępnych.');
        return;
      }
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
          url: "http://asiliwar.idl.pl/rezerwacje/public/index.php/api/rooms_free/"+$filter('date')($scope.reservation.since,'yyyy-MM-ddTHH:mm:ss.sssZ', 'UTC')+"/"+$filter('date')($scope.reservation.till,'yyyy-MM-ddTHH:mm:ss.sssZ', 'UTC')
        }).success(function(response) {
          $scope.available_rooms = response;
          $scope.chosen_rooms = [];
        });

        $http({
          method: 'get',
          url: "http://asiliwar.idl.pl/rezerwacje/public/index.php/api/room"
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
      for(var i=0; i<$scope.chosen_rooms.length; i++) {
        if($scope.chosen_rooms[i].number == number) {
          var roomsArray = $scope.chosen_rooms.splice(i, 1);
          $scope.available_rooms = $scope.available_rooms.concat(roomsArray);
          return;
        }
      }
    }

    $scope.saveChanges = function() {
      if($scope.chosen_rooms == undefined || $scope.chosen_rooms.length == 0) {
        alert('Wybierz co najmniej jeden pokój poprzez kliknięcie na wybrany pokój z listy dostępnych.');
        return;
      }
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
