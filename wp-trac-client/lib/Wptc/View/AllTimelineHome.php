<?php
/**
 * @namespace
 */
namespace Wptc\View;

use Wptc\View\AllProjectsHome;
use Wptc\Helper\ProjectHelper;

/**
 * the projects main class.
 */
class AllTimelineHome extends AllProjectsHome {

    /**
     * build content for timeline.
     */
    public function buildContent() {

        $content = wptc_widget_trac_timeline();

        return $content;
    }
}
