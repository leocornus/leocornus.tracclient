<?php
/**
 * @namespace
 */
namespace Wptc\View;

use Wptc\View\ProjectHome;
use Wptc\Helper\ProjectHelper;

/**
 * the class to render the view for all commits.
 */
class ProjectCommitsHome extends ProjectHome {

    /**
     * build content main for tickets list.
     */
    public function buildContent() {

        // build the filter for priority.

        $content = <<<EOT
<div id="project-content" class="container-fluid">
  <div>
    <table class="table table-striped table-hover table-responsive" 
           id="project-items">
      <thead>
        <tr class="success">
          <th class="col-md-1">Commit</th>
          <th class="NONEcol-md-7">Comment</th>
          <th class="col-md-2">Author</th>
          <th class="col-md-1">UAT</th>
          <th class="col-md-1">Prod</th>
          <th></th>
        </tr>
      </thead>
      <tfoot>
        <tr class="success">
          <th>Commit</th>
          <th>Comment</th>
          <th>Author</th>
          <th">UAT</th>
          <th">Prod</th>
          <th></th>
        </tr>
        <tr class="success">
          <th colspan="6">
  <div class="row">
    <div class="form-inline col-sm-6">
      <div class="form-group">
        <label for="commit-search">Filter by Search:</label>
        <input type="text" class="form-control input-sm" 
               id="commit-search"
               placeholder="comment owner">
      </div>
    </div>
    <div class="text-right col-sm-6" id="item-pager">
      Showing <span id="loaded-items" class="badge">0</span> of 
      <span id="total-items" class="badge">0</span> Commits!
      <a class="btn btn-success btn-xs" 
         id="load-more-commits"
      >Load More...</a>
    </div>
  </div>
          </th>
        </tr>
      </tfoot>
      <tbody>
        <tr>
          <td colspan="6" class="bg-info h3 text-center">Loading ...</td>
        </tr>
      </tbody>
    </table>
  </div>
</div> <!-- project-content -->
EOT;

        return $content;
    }
}
