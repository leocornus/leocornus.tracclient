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

        $content = <<<EOT
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
EOT;

        return $content;
    }
}
