'use strict';

var reservationServices = angular.module('frontendServices', ['ngResource']);

reservationServices.factory('Reservation', ['$resource', function($resource) {
	return $resource('http://localhost:8000/api/reservation/', {}, {
		query: {method: 'GET', params: {'with_locators': true}, isArray:true}
	});
}]);


reservationServices.factory('Locator', ['$resource', function($resource) {
	return $resource('http://localhost:8000/api/locator/:id', {}, {
		'update': { method:'PUT' }
	});
}]);


reservationServices.factory('LocatorByToken', ['$resource', function($resource) {
	return $resource('http://localhost:8000/api/locator_by_token/:token');
}]);