<?php
/**
 * @namespace
 */
namespace Wptc\View;

use Wptc\View\ProjectViewBase;

/**
 * the projects main class.
 */
class ProjectsHome extends ProjectViewBase {

    /**
     * render the page.
     */
    public function renderPage() {

        $header = $this->buildHeader();

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

        // footer.
        $footer = $this->buildFooter();

        $the_view = <<<VIEW
{$header}
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
     * build project panel for a project
     */
    public function buildProjectPanel($projectName) {
    }
}
