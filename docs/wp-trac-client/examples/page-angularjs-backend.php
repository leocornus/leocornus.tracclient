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
//wp_enqueue_style('wptc-bootstrap');

$ajax_url = admin_url('admin-ajax.php');
$detail_template = get_stylesheet_directory_uri() . 
    '/template/detail.html';
$list_template = get_stylesheet_directory_uri() . 
    '/template/list.html';
?>

<script type="text/javascript">
jQuery('html').attr('ng-app', 'project');
</script>

<h2>AngularJS Projects</h2>

<div ng-view calss="well"></div>

<script type="text/javascript">
jQuery(document).ready(function() {
angular.module('project', ['ngRoute', 'firebase'])
 
.value('fbURL', 'https://angularjs-projects.firebaseio.com/')
 
.factory('Projects', function($firebase, fbURL) {
  return $firebase(new Firebase(fbURL)).$asArray();
})
 
.config(function($routeProvider) {

  $routeProvider
    .when('/', {
      controller:'ListCtrl',
      templateUrl:'<?php echo $list_template; ?>' 
    })
    .when('/edit/:projectId', {
      controller:'EditCtrl',
      templateUrl: '<?php echo $detail_template; ?>' 
    })
    .when('/new', {
      controller:'CreateCtrl',
      templateUrl: '<?php echo $detail_template; ?>' 
    })
    .otherwise({
      redirectTo:'/'
    });
})
 
.controller('ListCtrlORG', function($scope, Projects) {
  $scope.projects = Projects;
})

.controller('ListCtrl', function($scope, $http) {
  var data = {
    'action' : 'wptc_trac_tickets',
  };
  $http.post('<?php echo $ajax_url;?>', data, {params: data})
  .success(function(response) {
      //alert(response);
      $scope.projects = response;
  });
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
