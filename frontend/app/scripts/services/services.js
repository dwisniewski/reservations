'use strict';

var reservationServices = angular.module('frontendServices', ['ngResource']);


reservationServices.factory('Reservation', ['$resource', function($resource) {
	return $resource('http://localhost:8000/api/reservation/:id', {}, {
		query: {method: 'GET', params: {'with_locators': true}, isArray:true},
        update: {method: 'PUT'}
	});
}]);



reservationServices.factory('Locator', ['$resource', function($resource) {
	return $resource('http://localhost:8000/api/locator/:id',{}, {
		update: {method: 'PUT'}
	});
}]);


reservationServices.factory('Room', ['$resource', function($resource) {
	return $resource('http://localhost:8000/api/room/:number',{}, {
		update: {method: 'PUT'}
	});
}]);



reservationServices.factory('LocatorByToken', ['$resource', function($resource) {
	return $resource('http://localhost:8000/api/locator_by_token/:token');
}]);


//Modal window used in various places
reservationServices.service('modalService', ['$uibModal',
    function ($uibModal) {

        var modalDefaults = {
            backdrop: true,
            keyboard: true,
            modalFade: true,
            templateUrl: 'modal.html'
        };

        var modalOptions = {
            closeButtonText: 'Close',
            actionButtonText: 'OK',
            headerText: 'Proceed?',
            bodyText: 'Perform this action?'
        };

        this.showModal = function (customModalDefaults, customModalOptions) {
            if (!customModalDefaults) customModalDefaults = {};
            customModalDefaults.backdrop = 'static';
            return this.show(customModalDefaults, customModalOptions);
        };

        this.show = function (customModalDefaults, customModalOptions) {
            //Create temp objects to work with since we're in a singleton service
            var tempModalDefaults = {};
            var tempModalOptions = {};

            //Map angular-ui modal custom defaults to modal defaults defined in service
            angular.extend(tempModalDefaults, modalDefaults, customModalDefaults);

            //Map modal.html $scope custom properties to defaults defined in service
            angular.extend(tempModalOptions, modalOptions, customModalOptions);

            if (!tempModalDefaults.controller) {
                tempModalDefaults.controller = ['$scope', '$uibModalInstance', function ($scope, $uibModalInstance) {
                    $scope.modalOptions = tempModalOptions;
                    $scope.modalOptions.ok = function (result) {
                        $uibModalInstance.close(result);
                    };
                    $scope.modalOptions.close = function (result) {
                        $uibModalInstance.dismiss('cancel');
                    };
                }];
            }

            return $uibModal.open(tempModalDefaults).result;
        };
}]);