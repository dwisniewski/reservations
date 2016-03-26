'use strict';

/**
 * @ngdoc function
 * @name frontendApp.controller:ReservationsCtrl
 * @description
 * # ReservationsCtrl
 * Controller of the frontendApp
 */
angular.module('frontendApp')
  .controller('ReservationsCtrl', ['$scope', '$location', 'Reservation', function ($scope, $location, Reservation) {

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
        Locator.remove({id: reservationID}, function(success) {
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
  }]).controller('ReservationCreationCtrl', ['$scope', '$location', 'Reservation',  function ($scope, $location, Reservation) {
    $scope.reservation = new Reservation();
    
    $scope.saveNew = function() {
      console.log($scope.reservation);
      Reservation.save($scope.reservation);
      $location.path('/reservations/');
    };
  }])

  .controller('ReservationEditionCtrl', ['$scope', '$routeParams', '$location', 'Reservation', function ($scope, $routeParams, $location, Reservation) {
    $scope.reservation = Reservation.get({id: $routeParams.id});
    
    $scope.saveChanges = function() {
      Reservation.update({id: $scope.reservation.id}, $scope.reservation);
      console.log($scope.reservation);
      $location.path('/reservations/');
    };
  }]);
