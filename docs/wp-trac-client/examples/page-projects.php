<?php
/**
 * Template Name: Trac Projects Page
 */

/**
 * enqueue resources.
 */
function enqueue_resources() {
    wp_enqueue_style('wptc-bootstrap');
    wp_enqueue_style('wptc-bootstrap-theme');
    wp_enqueue_script('wptc-bootstrap-js');
}
add_action('wp_enqueue_scripts', 'enqueue_resources');
?>
<html>
<head>
  <?php wp_head();?>
</head>
<body>

<div class="container">
  <div class="page-header">
    <form class="navbar-form navbar-right" role="search">
      <div class="form-group">
        <input type="text" class="form-control" placeholder="Search">
      </div>
      <button type="submit" class="btn btn-info">Go</button>
    </form>
    <h3>OPSpedia Projects / Name of the Project</h3>
    <p>Brief Description about this project: 
       Resize this responsive page to see the effect!</p> 
  </div>

  <div>
  <ul class="nav nav-pills">
    <li><a href="#">Project Home</a></li>
    <li class="active"><a href="#">Issues</a></li>
    <li><a href="#">Commits</a></li>
    <li><a href="#">Wiki</a></li>
    <li><a href="#">Milestones</a></li>
    <li><a href="#">Contributors</a></li>
    <li><a href="#">Profile</a></li>
    <li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
        Actions<span class="caret"></span>
      </a>
      <ul class="dropdown-menu">
        <li><a href="#">Report an Issue</a></li>
        <li role="separator" class="divider"></li>
        <li class="dropdown-header">My...</li>
        <li><a href="#">My Tickets</a></li>
        <li><a href="#">My Watchlist</a></li>
      </ul>
    </li>
  </ul>
  </div>

  <div class="alert alert-info">
  Summary of Issues: Total, closed, assigned, etc.
  </div>

  <div class="table-responsive">
    <table class="table table-striped table-hover">
      <thead>
        <tr class="success">
          <th>#</th>
          <th>Header</th>
          <th>Header</th>
          <th>Header</th>
          <th>Header</th>
        </tr>
      </thead>
      <tfoot>
        <tr class="success">
          <th>#</th>
          <th>Header</th>
          <th>Header</th>
          <th>Header</th>
          <th>Header</th>
        </tr>
      </tfoot>
      <tbody>
        <tr>
          <td>1,001</td>
          <td>Lorem</td>
          <td>ipsum</td>
          <td>dolor</td>
          <td>sit</td>
        </tr>
        <tr>
          <td>1,002</td>
          <td>amet</td>
          <td>consectetur</td>
          <td>adipiscing</td>
          <td>elit</td>
        </tr>
        <tr>
          <td>1,001</td>
          <td>Lorem</td>
          <td>ipsum</td>
          <td>dolor</td>
          <td>sit</td>
        </tr>
        <tr>
          <td>1,002</td>
          <td>amet</td>
          <td>consectetur</td>
          <td>adipiscing</td>
          <td>elit</td>
        </tr>
        <tr>
          <td>1,001</td>
          <td>Lorem</td>
          <td>ipsum</td>
          <td>dolor</td>
          <td>sit</td>
        </tr>
        <tr>
          <td>1,002</td>
          <td>amet</td>
          <td>consectetur</td>
          <td>adipiscing</td>
          <td>elit</td>
        </tr>
        <tr>
          <td>1,001</td>
          <td>Lorem</td>
          <td>ipsum</td>
          <td>dolor</td>
          <td>sit</td>
        </tr>
        <tr>
          <td>1,002</td>
          <td>amet</td>
          <td>consectetur</td>
          <td>adipiscing</td>
          <td>elit</td>
        </tr>
      </tbody>
    </table>
  <div>

  <div class="well"> <!-- Fat Footer -->
    <div class="row">
      <div class="col-sm-4">
        <h2>Column 1</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit...</p>
        <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris...</p>
      </div>
      <div class="col-sm-4">
        <h2>Column 2</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit...</p>
        <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris...</p>
      </div>
      <div class="col-sm-4">
        <h2>Column 3</h2> 
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit...</p>
        <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris...</p>
      </div>
    </div>
  </div>
</div><!-- /container -->

<?php wp_footer();?>
</body>
</html>
