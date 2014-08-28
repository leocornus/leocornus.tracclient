<?php

/**
 * Template Name: Angular Testing Page
 */

get_header();
wp_enqueue_script('wptc-angular-core');
?>

<script type="text/javascript">
jQuery('html').attr('ng-app', 'todoApp');

jQuery(document).ready(function() {
// the todo application module.
angular.module('todoApp', [])
  .controller('TodoController', ['$scope', function($scope) {
    $scope.todos = [
      {text:'learn angular', done:true},
      {text:'build an angular app', done:false}];
 
    $scope.addTodo = function() {
      $scope.todos.push({text:$scope.todoText, done:false});
      $scope.todoText = '';
    };
 
    $scope.remaining = function() {
      var count = 0;
      angular.forEach($scope.todos, function(todo) {
        count += todo.done ? 0 : 1;
      });
      return count;
    };
 
    $scope.archive = function() {
      var oldTodos = $scope.todos;
      $scope.todos = [];
      angular.forEach(oldTodos, function(todo) {
        if (!todo.done) $scope.todos.push(todo);
      });
    };
  }]);
});
</script>

<div>
  <label>Name:</label>
  <input type="text" ng-model="yourName" 
         placeholder="Enter a name here">
  <hr>
  <h1>Hello {{yourName}}!</h1>
</div>

<h2>TODO List</h2>

<div ng-controller="TodoController">
  <span>{{remaining()}} of {{todos.length}} remaining</span>
  [ <a href="" ng-click="archive()">archive</a> ]
  <ul class="unstyled">
    <li ng-repeat="todo in todos">
      <input type="checkbox" ng-model="todo.done">
      <span class="done-{{todo.done}}">{{todo.text}}</span>
    </li>
  </ul>
  <form ng-submit="addTodo()">
    <input type="text" ng-model="todoText"  size="30"
           placeholder="add new todo here">
    <input class="btn-primary" type="submit" value="add">
  </form>
</div>

<?php
get_footer();
