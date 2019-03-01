<!DOCTYPE html>
<html>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
    <body ng-app="myApp">

        <div ng-controller="myCtrl">

            First Name: <input type="text" ng-model="firstName"><br>
            Last Name: <input type="text" ng-model="lastNamye"><br>
            <br>
            Full Name: {{firstName + " " + lastNamye}}

        </div>
        
        <div ng-controller="myCtrl2">

            First Name: <input type="text" ng-model="firstName"><br>
            Last Name: <input type="text" ng-model="lastName"><br>
            <br>
            Full Name: {{firstName + " " + lastName}}

        </div>

        <script>
            
            var app = angular.module('myApp', []);
            
            app.controller('myCtrl', function ($scope) {
                
                $scope.firstName = "John";
                $scope.lastNamye = "Doe";
            });
            
            app.controller('myCtrl2', function ($scope) {
                
                $scope.firstName = "Sean";
                $scope.lastName  = "Rock";
            });
            
        </script> 

    </body>
</html>