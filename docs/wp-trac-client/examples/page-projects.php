<?php
/**
 * Template Name: Trac Projects Page
 *
 * testing the projects prototype.
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
  <div class="jumbotron">
    <h2>Name of the Project</h2>
    <p>Brief Description about this project: 
       Resize this responsive page to see the effect!</p> 
  </div>

  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">All Projects</a>
      </div>
      <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
          <li class="active"><a href="#">Issues</a></li>
          <li><a href="#">Commits</a></li>
          <li><a href="#">Wiki</a></li>
          <li><a href="#">Milestones</a></li>
          <li><a href="#">Contributors</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Actions<span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="#">Report an Issue</a></li>
              <li role="separator" class="divider"></li>
              <li class="dropdown-header">My...</li>
              <li><a href="#">My Tickets</a></li>
              <li><a href="#">My Watchlist</a></li>
            </ul>
          </li>
        </ul>
      </div><!--/.nav-collapse -->
    </div><!--/.container-fluid -->
  </nav>

  <div class="alert alert-info">
  Summary of Issues: Total, closed, assigned, etc.
  </div>

  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Header</th>
          <th>Header</th>
          <th>Header</th>
          <th>Header</th>
        </tr>
      </thead>
      <tfoot>
        <tr>
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

  <div class="jumbotron"> <!-- Fat Footer -->
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
