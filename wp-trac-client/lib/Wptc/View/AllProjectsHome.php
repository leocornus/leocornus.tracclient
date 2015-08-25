<?php
/**
 * @namespace
 */
namespace Wptc\View;

use Wptc\View\ProjectViewBase;
use Wptc\Helper\ProjectHelper;

/**
 * the projects main class.
 */
class AllProjectsHome extends ProjectViewBase {

    /**
     * render the page.
     */
    public function renderPage() {

        $header = $this->buildHeader();
        $navbar = $this->buildNavbar();
        $content = $this->buildContent();
        // footer.
        $footer = $this->buildFooter();

        $the_view = <<<VIEW
{$header}
{$navbar}
{$content}
{$footer}
VIEW;

        return $the_view;
    }

    /**
     * build header for the homepage.
     */
    public function buildHeader() {

        $content = <<<EOT
<div id="projects-header" class="jumbotron">
  <h1><span class="text-success">WP Trac Projects</span></h1>
  <p class="text-info">Open Source, Open Mind, Project Management in Agile</p>
</div>
EOT;

        return $content;
    }

    /**
     * build nav bar for all projects page.
     */
    public function buildNavbar() {

        $helper = new ProjectHelper();
        $total_projects = $helper->getAllProjectsTotal();
        $total_tickets = $helper->getAllTicketsTotal();
        $total_commits = $helper->getAllCommitsTotal();

        $nav = <<<EOT
<div id="project-nav" class="container-fluid h4">
  <ul class="nav nav-tabs">
    <li class="active"><a href="#">
      <span class="badge">{$total_projects}</span> Projects</a></li>
    <li><a href="#">
      <span class="badge">{$total_tickets}</span> Tickets</a>
    </li>
    <li><a href="#">
      <span class="badge">{$total_commits}</span> Commits
    </a></li>
    <li class="dropdown pull-right">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
        Actions<span class="caret"></span>
      </a>
      <ul class="dropdown-menu">
        <li><a href="#">Create Project</a></li>
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
     * build content section for all projects list.
     */
    public function buildContent() {

        $content = <<<EOT
<div id="projects-list" class="container-fluid">
  <h2 class="text-center">Loading ...</h2>
</div>

<div class="container-fluid">
  <div class="alert alert-info h4"><div class="row">
    <div class="form-inline col-sm-6">
      <div class="form-group">
        <label for="project-search">Search:</label>
        <input type="text" class="form-control" id="project-search"
               placeholder="Search Project">
      </div>
    </div>
    <div class="text-right col-sm-6" id="projects-pager">
      Showing <span id="loaded-items" class="badge">20</span> of 
      <span id="total-items" class="badge">120</span> Projects
      <a class="btn btn-success" id="projects-load-more">
        Load More...
      </a>
    </div>
  </div></div>
</div>
EOT;

        return $content;
    }

    /**
     * build project panel for a project
     */
    public function buildProjectPanel($projectName) {
    }
}
