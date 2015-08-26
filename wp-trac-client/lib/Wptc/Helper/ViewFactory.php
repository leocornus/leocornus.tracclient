<?php
/**
 * The Wptc View Helper class.
 */
namespace Wptc\Helper;

use Wptc\Context\RequestContext;
use Wptc\Context\AllProjectsRequestContext;
//use Wptc\Context\AllTicketsRequestContext;
use Wptc\View\AllProjectsHome;
use Wptc\View\AllTicketsHome;
use Wptc\View\ProjectTicketsHome;

/**
 * helper class to create request context and generate views.
 */
class ViewFactory {

    /**
     * the constructor.
     */
    public function __construct() {

        // the project param.
        $this->project_name = $this->getUrlParam('project');
        // the tab param.
        $this->tab_name = $this->getUrlParam('tab');
    }

    /**
     * create request context based on the page url.
     */
    public function createContext() {

        $context = new RequestContext();
        if(empty($this->project_name)) {
            $context->setCookieStates(-3600);
            // all projects page
            $context = new AllProjectsRequestContext();
        }

        return $context;
    }

    /**
     * facility class to render the page view.
     */
    public function generateView($context) {

        if(empty($this->project_name)) {
            if(!empty($this->tab_name)) {
                switch($this->tab_name) {
                    case 'tickets':
                        $the_page = new AllTicketsHome($context);
                        break;
                }
            }
            if(empty($the_page)){
                // default is the all projects homepage.
                $the_page = new AllProjectsHome($context);
            }
            echo $the_page->renderPage();
        } else {
            $projectHome = new ProjectTicketsHome($context);
            echo $projectHome->renderPage();
        }
    }

    /**
     * get a URL parameter's value, mainly from the $_GET
     */
    public function getUrlParam($param) {

        // try to find the selected theme name
        if (array_key_exists($param, $_GET)) {
            $value = $_GET[$param];
        } else {
            $value = '';
        }

        return $value;
    }
}
