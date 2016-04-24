'use strict';

/**
 * @ngdoc overview
 * @name frontendApp
 * @description
 * # frontendApp
 *
 * Main module of the application.
 */
angular
  .module('frontendApp', [
    'ngAnimate',
    'ngCookies',
    'ngMessages',
    'ngResource',
    'ngRoute',
    'ngSanitize',
    'ngTouch',
    'frontendServices',
    'smart-table',
    'ui.bootstrap',
    'ui.calendar',
    'ui.bootstrap.datetimepicker'
  ]).config(['$sceDelegateProvider', function($sceDelegateProvider) {
    $sceDelegateProvider.resourceUrlWhitelist([
      'self', 'http://localhost:8000/api/**'
    ]);

  }])
  .config(function ($routeProvider) {
    $routeProvider
      .when('/', {
        templateUrl: 'views/main.html',
        controller: 'MainCtrl',
        controllerAs: 'main'
      })
      .when('/reservations', {
        templateUrl: 'views/reservations/reservations.html',
        controller: 'ReservationsCtrl',
        controllerAs: 'reservations'
      }).when('/reservation-create/', {
        templateUrl: 'views/reservations/reservations.create.html',
        controller: 'ReservationCreationCtrl',
        controllerAs: 'reservationCreate'
      }).when('/reservation-edit/:id', {
        templateUrl: 'views/reservations/reservations.update.html',
        controller: 'ReservationEditionCtrl',
        controllerAs: 'reservationUpdate'
      }).when('/room-find', {
        templateUrl: 'views/rooms_finder.html',
        controller: 'RoomsFindCtrl',
        controllerAs: 'roomFind'
      }).when('/rooms', {
        templateUrl: 'views/rooms/rooms.html',
        controller: 'RoomsCtrl',
        controllerAs: 'rooms'
      }).when('/room-create/', {
        templateUrl: 'views/rooms/rooms.create.html',
        controller: 'RoomCreationCtrl',
        controllerAs: 'roomCreate'
      }).when('/room-edit/:id', {
        templateUrl: 'views/rooms/rooms.update.html',
        controller: 'RoomEditionCtrl',
        controllerAs: 'roomUpdate'
      }).when('/locators', {
        templateUrl: 'views/locators/locators.html',
        controller: 'LocatorsCtrl',
        controllerAs: 'locators'
      }).when('/locator-create', {
        templateUrl: 'views/locators/locators.create.html',
        controller: 'LocatorCreationCtrl',
        controllerAs: 'locatorCreate'
      }).when('/locator-edit/:id', {
        templateUrl: 'views/locators/locators.update.html',
        controller: 'LocatorEditionCtrl',
        controllerAs: 'locatorUpdate'
      })
      .otherwise({
        redirectTo: '/'
      });
  });
