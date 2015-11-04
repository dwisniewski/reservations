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
    'smart-table'
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
        templateUrl: 'views/reservations.html',
        controller: 'ReservationsCtrl',
        controllerAs: 'reservations'
      }).when('/rooms', {
        templateUrl: 'views/rooms.html',
        controller: 'RoomsCtrl',
        controllerAs: 'reservations'
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
