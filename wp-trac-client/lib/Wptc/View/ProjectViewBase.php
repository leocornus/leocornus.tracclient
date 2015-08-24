<?php
/**
 * @namespace
 */
namespace Wptc\View;

/**
 * The base class for all views related to a project.
 * this class will 
 * - define the base layout for all project views.
 * - provide the basic methods to render / generate the 
 *   common sections for a page, such as header, nabar, 
 *   footer, etc.
 */
class ProjectViewBase {

    /**
     */
    protected $context = null;

    /**
     * constructor
     */
    public function __construct($context) {

        $this->context = $context;
    }

    /**
     * render the page, with basic general layout.
     */
    public function renderPage() {

        $header = $this->buildHeader();
        $navbar = $this->buildNavbar();
        $content = $this->buildContent();
        $footer = $this->buildFooter();

        $page_view = <<<VIEW
{$header}
{$navbar}
{$content}
{$footer}
VIEW;

        return $page_view;
    }

    /**
     * build header, the gneral header for a project.
     */
    public function buildHeader() {

        $projects_url = "/projects";
        $project_name = $this->context->getState('project');
        $project_url = "/projects?project={$project_name}";
        $project = wptc_get_project($project_name);

        $header = <<<EOT
<div class="page-header" id="project-header">
  <form class="navbar-form navbar-right" role="search">
    <div class="form-group">
      <input type="text" class="form-control" placeholder="Search">
    </div>
    <button type="submit" class="btn btn-success">Go</button>
  </form>
  <h3>
    <a href="{$projects_url}"> All Projects</a> / 
    <a href="{$project_url}">{$project_name}</a>
  </h3>
  <p>{$project['description']}</p> 
</div> <!-- project-header -->
EOT;

        return $header;
    }

    /**
     * build nav bar, using the nav-tabs class.
     * - navigation bar, including the action drop down.
     * - the context object will tell which tab we are in now.
     */
    public function buildNavbar() {

        // main query to get the total number.
        $main_query = $this->context->buildMainQuery();
        $ids = wptc_ticket_query($main_query, 0);
        $total = count($ids);

        $nav = <<<EOT
<div id="project-nav" class="container-fluid h4">
  <ul class="nav nav-tabs">
    <li><a href="#">Project Home</a></li>
    <li class="active"><a href="#">
      <span class="badge">{$total}</span> Tickets</a>
    </li>
    <li><a href="#">
      <span class="badge"></span> Commits
    </a></li>
    <li class="dropdown pull-right">
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
</div> <!-- project-nav -->
EOT;

        return $nav;
    }

    /**
     * build content. the content section will basically have:
     * - filter bar based on what content is it.
     * - content, maybe list of items or summary panels.
     * - pager if needed.
     *
     * the context object will tell:
     * - what content to serve.
     * - what filter it will provide
     * - waht actions it should have
     */
    public function buildContent() {

        // empty container for now.
        $content = <<<EOT
<div id="project-content" class="container-fluid">
</div> <!-- project-content -->
EOT;

        return $content;
    }

    /**
     * build footer.
     */
    public function buildFooter() {

        // here is a sample footer:
        // a 3-column row in a well class.
        $footer = <<<EOT
<div class="well" id="local-project-footer">
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
</div> <!-- local-project-footer -->
EOT;

        if(has_filter('wptc_project_view_footer')) {
            $footer = apply_filters('wptc_project_view_footer',
                                    $footer);
        }

        return $footer; 
    }

    // =====================
    // some mics components / setcions.
    // =====================

    /**
     * this one will build a dropdown with a list owner's name
     * by default it implemented as dropdown menu.
     *
     * NOTE:
     * This is just a markup for now.
     * we should pass a list user names
     */
    public function buildFilterOwner() {

        $owners = <<<OWNER
<div class="btn-group">
  <a href="#" class="btn btn-success btn-xs dropdown-toggle"
              data-toggle="dropdown">
    Owner <span class="caret"></span>
  </a>
  <ul class="dropdown-menu">
    <li><a href="#">
       <span class="glyphicon glyphicon-check"></span>
       Action</a></li>
    <li><a href="#">
       <span class="glyphicon glyphicon-check"></span>
       Another action</a></li>
    <li><a href="#">
       <span class="glyphicon glyphicon-check"></span>
       Something else here</a></li>
    <li><a href="#">
       <span class="glyphicon glyphicon-unchecked"></span>
       Separated link</a></li>
  </ul>
</div>
OWNER;

        return $owners;
    }
}
