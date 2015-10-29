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
        $modals = $this->buildModals();

        $the_view = <<<VIEW
{$header}
{$navbar}
{$content}
{$footer}
{$modals}
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

        $base_url = "/projects";
        $tab = $this->context->getState('tab');
        if(empty($tab)) {
            $home_active = ' class="active"';
        } else {
            switch($tab) {
                case 'tickets':
                    $tickets_active = ' class="active"';
                    break;
                case 'commits':
                    $commits_active = ' class="active"';
                    break;
            }
        }

        $helper = new ProjectHelper();
        $total_projects = $helper->getAllProjectsTotal();
        $total_tickets = $helper->getAllTicketsTotal();
        $total_commits = $helper->getAllCommitsTotal();

        $nav = <<<EOT
<div id="project-nav" class="container-fluid h4">
  <ul class="nav nav-tabs">
    <li {$home_active}><a href="{$base_url}">
      <span class="badge">{$total_projects}</span> Projects</a></li>
    <li {$tickets_active}><a href="{$base_url}?tab=tickets">
      <span class="badge">{$total_tickets}</span> Tickets</a>
    </li>
    <li {$commits_active}><a href="{$base_url}?tab=commits">
      <span class="badge">{$total_commits}</span> Commits
    </a></li>
    <li {$media_active}><a nohref="{$base_url}?tab=media">
      <span class="badge">{$total_media}</span> Media
    </a></li>
    <li class="dropdown pull-right">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
        Actions<span class="caret"></span>
      </a>
      <ul class="dropdown-menu">
        <li><a href="" data-toggle="modal" data-target="#newProject">
          <span class="glyphicon glyphicon-list-alt"></span>
          Create Project
        </a></li>
        <li><a href="#">
          <span class="glyphicon glyphicon-pencil"></span>
          Create Ticket
        </a></li>
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
      Showing <span id="loaded-items" class="badge">0</span> of 
      <span id="total-items" class="badge">0</span> Projects
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

    /**
     * build modals.
     */
    public function buildModals() {

        $modals = <<<MODAL
<div id="newProject" class="modal fade" role="dialog">
  <div class="modal-dialog" id="projectDialog">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" 
                data-dismiss="modal">x</button>
        <h3 class="modal-title">Create New Project</h3>
      </div>

      <div class="modal-body">
<div class="form-horizontal">
  <fieldset>
    <div class="form-group">
      <label for="inputName" class="col-lg-4 control-label">Project Name</label>
      <div class="col-lg-8">
        <input type="text" class="form-control" id="inputName" placeholder="set name for your project">
        <span class="help-block">Using only letter and number for project name. NO whitespaces and special chareters</span>
        <span class="form-control-feedback"></span>
      </div>
    </div>
    <div class="form-group">
      <label for="projectDescription" class="col-lg-4 control-label">Project Description</label>
      <div class="col-lg-8">
        <textarea class="form-control" rows="3" id="projectDescription"></textarea>
        <span class="help-block">A longer block of help text that breaks onto a new line and may extend beyond one line.</span>
      </div>
    </div>
    <div class="form-group">
      <label for="inputOwners" class="col-lg-4 control-label">
        Project Owners
      </label>
      <div class="col-lg-8" id="owners-col">
        <textarea class="form-control" id="projectOwners" rows="2"
                placeholder="set project owners"></textarea>
        <span class="help-block">Find user by typing user's full name or login name.</span>
      </div>
    </div>
  </fieldset>
</div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>
MODAL;

        return $modals;
    }
}
