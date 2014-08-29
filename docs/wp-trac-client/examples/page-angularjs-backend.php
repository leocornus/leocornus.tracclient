<?php
/**
 * Template Name: Angular Backend Testing
 * 
 * Try to wire up AnuglarJS projects tutorial with 
 * wp-trac-client backend.
 */
get_header();
wp_enqueue_script('wptc-angularjs-core');
wp_enqueue_script('wptc-angularjs-resource');
wp_enqueue_script('wptc-angularjs-route');
wp_enqueue_script('wptc-angularfire');
wp_enqueue_script('wptc-firebase');
wp_enqueue_style('wptc-bootstrap');
?>

<script type="text/javascript">
jQuery('html').attr('ng-app', 'project');
</script>

<h2>AngularJS Projects</h2>

<div ng-view></div>

<script type="text/javascript">
jQuery(document).ready(function() {
angular.module('project', ['ngRoute', 'firebase'])
 
.value('fbURL', 'https://angularjs-projects.firebaseio.com/')
 
.factory('Projects', function($firebase, fbURL) {
  return $firebase(new Firebase(fbURL)).$asArray();
})
 
.config(function($routeProvider) {

  // the projects list html.
  var list = '<input type="text" ng-model="search" class="search-query" placeholder="Search">' + 
'<table>' +
'  <thead>' +
'  <tr>' +
'    <th>Project</th>' +
'    <th>Description</th>' +
'    <th><a href="#/new"><i class="icon-plus-sign"></i></a></th>' +
'  </tr>' +
'  </thead>' +
'  <tbody>' +
'  <tr ng-repeat="project in projects | filter:search | orderBy:\'name\'">' +
'    <td><a ng-href="{{project.site}}" target="_blank">{{project.name}}</a></td>' +
'    <td>{{project.description}}</td>' +
'    <td>' +
'      <a ng-href="#/edit/{{project.$id}}"><i class="icon-pencil"></i></a>' +
'    </td>' +
'  </tr>' +
'  </tbody>' +
'</table>';
  // the details html template
  var detail = '<form name="myForm">' + 
'  <div class="control-group" ng-class="{error: myForm.name.$invalid && !myForm.name.$pristine}">' +
'    <label>Name</label>' +
'    <input type="text" name="name" ng-model="project.name" required>' +
'    <span ng-show="myForm.name.$error.required && !myForm.name.$pristine" class="help-inline">' +
'        Required {{myForm.name.$pristine}}</span>' +
'  </div>' +
' ' +
'  <div class="control-group" ng-class="{error: myForm.site.$invalid && !myForm.site.$pristine}">' +
'    <label>Website</label>' +
'    <input type="url" name="site" ng-model="project.site" required>' +
'    <span ng-show="myForm.site.$error.required && !myForm.site.$pristine" class="help-inline">' +
'        Required</span>' +
'    <span ng-show="myForm.site.$error.url" class="help-inline">' +
'        Not a URL</span>' +
'  </div>' +
' ' +
'  <label>Description</label>' +
'  <textarea name="description" ng-model="project.description"></textarea>' +
' ' +
'  <br>' +
'  <a href="#/" class="btn">Cancel</a>' +
'  <button ng-click="save()" ng-disabled="myForm.$invalid"' +
'          class="btn btn-primary">Save</button>' +
'  <button ng-click="destroy()"' +
'          ng-show="project.$id" class="btn btn-danger">Delete</button>' +
'</form>';

  $routeProvider
    .when('/', {
      controller:'ListCtrl',
      template: list
    })
    .when('/edit/:projectId', {
      controller:'EditCtrl',
      template: detail
    })
    .when('/new', {
      controller:'CreateCtrl',
      template: detail
    })
    .otherwise({
      redirectTo:'/'
    });
})
 
.controller('ListCtrl', function($scope, Projects) {
  $scope.projects = Projects;
})
 
.controller('CreateCtrl', function($scope, $location, $timeout, Projects) {
  $scope.save = function() {
      Projects.$add($scope.project).then(function(data) {
          $location.path('/');
      });
  };
})
 
.controller('EditCtrl',
  function($scope, $location, $routeParams, Projects) {
    var projectId = $routeParams.projectId,
        projectIndex;
 
    $scope.projects = Projects;
    projectIndex = $scope.projects.$indexFor(projectId);
    $scope.project = $scope.projects[projectIndex];
 
    $scope.destroy = function() {
        $scope.projects.$remove($scope.project).then(function(data) {
            $location.path('/');
        });
    };
 
    $scope.save = function() {
        $scope.projects.$save($scope.project).then(function(data) {
           $location.path('/');
        });
    };
});
});
</script>

<?php
get_footer();
