`AngularJS Introduction Story <AngularJS-Introduction-Story.rst>`_
> Explore the options for AngularJS to communicate with 
WordPress backend. 

Options That we are looking at:

- Angular's `$http`_ service. AngularJS tutioial step 5 
  `XHRs & Dependency Injection`_ offers a simple and details example.
- Angular's `$resource`_ service.

Code Samples
------------

Here is a quick sample about how `$http`_ service talk to
WordPress wp_ajax actions::

  .controller('ListCtrl', function($scope, $http) {
    // post data
    data = {
      'action': 'the_wp_action'
    }
    $http.post(ajax_url, data, {params:data})
    .success(function(response) {
        $scope.projects = response;
    });
  });

.. _$http: https://docs.angularjs.org/api/ng/service/$http
.. _$resource: https://docs.angularjs.org/api/ngResource/service/$resource
.. _XHRs & Dependency Injection: https://docs.angularjs.org/tutorial/step_05
