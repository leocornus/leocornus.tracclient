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
  <h2 class="text-center">Loading ...</h2>
</div>

<div class="container-fluid">
  <div class="alert alert-info text-right h4" id="projects-pager">
    Showing <span id="loaded-items" class="badge">20</span> of 
    <span id="total-items" class="badge">120</span> Projects
    <a class="btn btn-success" id="projects-load-more">
      Load More...
    </a>
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
  <h1><span class="text-success">WP Trac Projects</span></h1>
  <p class="text-info">Open Source, Open Mind, Project Management in Agile</p>
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
