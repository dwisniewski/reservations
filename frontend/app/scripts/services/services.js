var reservationServices = angular.module('frontendServices', ['ngResource']);

reservationServices.factory('Reservation', ['$resource', function($resource) {
	return $resource('http://localhost:8000/api/reservation/', {}, {
		query: {method: 'GET', params: {'with_locators': true}, isArray:true}
	});
}]);