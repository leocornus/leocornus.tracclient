<?php
/**
 * @namespace
 */
namespace Wptc\View;

/**
 * the projects main class.
 */
class ProjectsHome {

    /**
     * constructor
     */
    public function __construct($context) {

    }

    /**
     * render the page.
     */
    public function renderPage($echo=false) {

        $header = $this->buildProjectsHeader();

        $content = <<<EOT
{$header}
<div id="projects-list" class="container-fluid">
  <div class="row">
    <div class="col-sm-4">
      <h2><a href="?project=TracCore">TracCore</a></h2>
      <p>
        <button type="button" class="btn btn-xs btn-danger">
          <span class="badge">809</span> Tickets
        </button>
        <button type="button" class="btn btn-xs btn-primary">
          <span class="badge">1002</span> Commits 
        </button>
        <button type="button" class="btn btn-xs btn-success">
          <span class="badge">15</span> Contributors 
        </button>
        <button type="button" class="btn btn-xs btn-warning">
          <span class="badge">200</span> Wiki Pages 
        </button>
        <button type="button" class="btn btn-xs btn-info">
          <span class="badge">80</span> Blog Posts
        </button>
      </p>
      <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris...</p>
    </div>
    <div class="col-sm-4">
      <h2>Column 1.2</h2>
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit...</p>
      <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris...</p>
    </div>
    <div class="col-sm-4">
      <h2>Column 1.3</h2> 
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit...</p>
      <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris...</p>
    </div>
  </div>

  <div class="row">
    <div class="col-sm-4">
      <h2>Column 1.1</h2>
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit...</p>
      <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris...</p>
    </div>
    <div class="col-sm-4">
      <h2>Column 1.2</h2>
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit...</p>
      <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris...</p>
    </div>
    <div class="col-sm-4">
      <h2>Column 1.3</h2> 
      <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit...</p>
      <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris...</p>
    </div>
  </div>
  
  <div class="alert alert-info text-right h4" id="projects-pager">
    Showing <span id="loaded-items" class="badge">20</span> of 
    <span id="loaded-items" class="badge">120</span> Projects 
    <button type="button" class="btn btn-success"
     id="projects-load-more">
      Load More...
    </button>
  </div>
</div>
EOT;

        return $content;
    }

    /**
     * build header for the homepage.
     */
    public function buildProjectsHeader() {

        $content = <<<EOT
<div id="projects-header" class="jumbotron">
  <h1>WP Trac Projects</h1>
  <p>Open Source, Open Mind, Project Management in Agile</p>
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
