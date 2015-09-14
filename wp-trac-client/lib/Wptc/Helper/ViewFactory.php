<?php
/**
 * The Wptc View Helper class.
 */
namespace Wptc\Helper;

use Wptc\Context\ProjectRequestContext;
use Wptc\Context\AllProjectsRequestContext;
use Wptc\View\AllProjectsHome;
use Wptc\View\AllTicketsHome;
use Wptc\View\AllCommitsHome;
use Wptc\View\ProjectHome;
use Wptc\View\ProjectTicketsHome;

/**
 * helper class to create request context and generate views.
 * It will be used in the following cases:
 * - reload page.
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

        if(empty($this->project_name)) {
            // all projects page
            $context = new AllProjectsRequestContext();
        } else {
            $context = new ProjectRequestContext();
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
                    case 'commits':
                        $the_page = new AllCommitsHome($context);
                        break;
                }
            }
            if(empty($the_page)){
                // default is the all projects homepage.
                $the_page = new AllProjectsHome($context);
            }
            echo $the_page->renderPage();
        } else {
            if(!empty($this->tab_name)) {
                switch($this->tab_name) {
                    case 'tickets':
                        $the_page = new ProjectTicketsHome($context);
                        break;
                    case 'commits':
                        $the_page = new ProjectCommitsHome($context);
                        break;
                }
            }
            if(empty($the_page)){
                // default is the all projects homepage.
                $the_page = new ProjectHome($context);
            }
            echo $the_page->renderPage();
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
