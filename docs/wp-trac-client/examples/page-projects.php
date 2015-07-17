<?php
/**
 * Template Name: Trac Projects Page
 */
add_action('wp_enqueue_scripts', 'wptc_enqueue_project_resources');
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
    <h3>All Projects / Name of the Project</h3>
    <p>Brief Description about this project: 
       Resize this responsive page to see the effect!</p> 
  </div>

  <div id="project-nav">
  <!-- ul class="nav nav-pills" -->
  <nav class="navbar navbar-default">
  <div class="container-fluid">
    <ul class="nav navbar-nav">
      <li><a href="#">Project Home</a></li>
      <li class="active"><a href="#">Issues</a></li>
      <li><a href="#">Commits</a></li>
      <li><a href="#">Wiki</a></li>
      <li><a href="#">Milestones</a></li>
      <li><a href="#">Contributors</a></li>
      <li><a href="#">Profile</a></li>
    </ul>
    <ul class="nav navbar-nav navbar-right">
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
  </nav>
  </div> <!-- project-nav -->

  <div id="project-content">
  <div class="h4" id="summary">
    <span>
      Filters:
      <div class="btn-group">
        <a href="#" class="btn btn-info btn-xs dropdown-toggle"
                    data-toggle="dropdown">
          Owner<span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
          <li><a href="#">Action</a></li>
          <li><a href="#">Another action</a></li>
          <li><a href="#">Something else here</a></li>
          <li class="divider"></li>
          <li><a href="#">Separated link</a></li>
        </ul>
      </div>
      <div class="btn-group">
        <a href="#" class="btn btn-info btn-xs dropdown-toggle" 
                    data-toggle="dropdown" aria-expanded="false">
          Status <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
          <li><a href="#">Action</a></li>
          <li><a href="#">Another action</a></li>
          <li><a href="#">Something else here</a></li>
          <li class="divider"></li>
          <li><a href="#">Separated link</a></li>
        </ul>
      </div>
    </span>
    <span id="numbers" class="pull-right">
      Summary:
      <span class="label label-primary">140 in total</span>
      <span class="label label-success">10 closed</span>
      <span class="label label-warning">20 assigned</span>
      <span class="label label-danger">3 critical</span>
    </span>
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
    <div id="loadmore" class="h4 text-right">
      Showing 20 of 120 tickets!
      <a href="#" class="btn btn-success btn-sm disabled">Load More</a>
    </div>
  </div> <!-- table responsive -->
  </div> <!-- project-content -->

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
