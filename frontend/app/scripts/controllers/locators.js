'use strict';

/**
 * @ngdoc function
 * @name frontendApp.controller:ReservationsCtrl
 * @description
 * # ReservationsCtrl
 * Controller of the frontendApp
 */


angular.module('frontendApp')
  .controller('LocatorsCtrl', ['$scope', '$location', 'Locator', 'LocatorByToken', 'modalService',  function ($scope, $location, Locator, LocatorByToken, modalService) {
    $scope.all_locators = Locator.query();
    $scope.search = {};
    
    $scope.search.keyUp = function() {
    	if($scope.search.input && $scope.search.input.length > 0) {
	    	LocatorByToken.query({'token': $scope.search.input}, function(data) {
	    		$scope.all_locators = data;
	    	});
		} else {
			$scope.all_locators = Locator.query();
		}
    };

/*
    $scope.locator.name.keyUp = function() {
      if($scope.locator.name.input && $scope.locator.name.input.length > 0) {
        $scope.locator.name.input = firstToUpperCase($scope.locator.name.input);
      }
    }
*/
    $scope.createLocator = function() {
    	$location.path('/locator-create/');
    };

    $scope.editLocator = function(userID) {
    	$location.path('/locator-edit/' + userID);
    };

    $scope.deleteLocator = function(userID) {
    	var modalOptions = {
            closeButtonText: 'Nie usuwaj',
            actionButtonText: 'Usuń lokatora',
            headerText: 'Czy usunąć lokatora?',
            bodyText: 'Czy na pewno chcesz usunąć lokatora?'
        };

        modalService.showModal({}, modalOptions).then(function (result) {
        	console.log('deleting' + userID);
	    	Locator.remove({id: userID}, function(success) {
				$("table").find("[data-id='" + userID + "']").fadeOut();
	    	});
        }, null);



    };
  }])

  .controller('LocatorCreationCtrl', ['$scope', '$location', 'Locator',  function ($scope, $location, Locator) {
  	$scope.locator = new Locator();
  	$scope.saveNew = function() {
  		console.log($scope.locator);
  		Locator.save($scope.locator);
  		$location.path('/locators/');
  	};
  }])

  .controller('LocatorEditionCtrl', ['$scope', '$routeParams', '$location', 'Locator', function ($scope, $routeParams, $location, Locator) {
  	$scope.locator = Locator.get({id: $routeParams.id});
  	
  	$scope.saveChanges = function() {
  		Locator.update({id: $scope.locator.id}, $scope.locator);
  		console.log($scope.locator);
  		$location.path('/locators/');
  	};
  }]).directive('capitalize', function() {
   return {
     require: 'ngModel',
     link: function(scope, element, attrs, modelCtrl) {
        var capitalize = function(inputValue) {
           if(inputValue == undefined) inputValue = '';
           var capitalized = inputValue.substr(0, 1).toUpperCase() + inputValue.substr(1);

           if(capitalized !== inputValue) {
              modelCtrl.$setViewValue(capitalized);
              modelCtrl.$render();
            }         
            return capitalized;
         }
         modelCtrl.$parsers.push(capitalize);
         capitalize(scope[attrs.ngModel]);  // capitalize initial value
     }
   };
}).directive('removespaces', function() {
   return {
     require: 'ngModel',
     link: function(scope, element, attrs, modelCtrl) {
        var removespaces = function(inputValue) {
           if(inputValue == undefined) inputValue = '';
           var removed_spaces = inputValue.replace(/\s/g, '');
           if(removed_spaces !== inputValue) {
              modelCtrl.$setViewValue(removed_spaces);
              modelCtrl.$render();
            }         
            return removed_spaces;
         }
         modelCtrl.$parsers.push(removespaces);
         removespaces(scope[attrs.ngModel]);  // remove_spaces of initial value
     }
   };
});
