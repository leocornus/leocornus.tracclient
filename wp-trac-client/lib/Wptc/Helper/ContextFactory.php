<?php
/**
 * The Wptc helper class to create request context.
 */
namespace Wptc\Helper;

use Wptc\Context\RequestContext;
use Wptc\Context\AllProjectsRequestContext;
//use Wptc\Context\AllTicketsRequestContext;

/**
 * helper class to create request context 
 * it will mainly used for the backend AJAX call back functions.
 */
class ContextFactory {

    /**
     * the constructor.
     */
    public function __construct() {

        // the project param.
        $this->project_name = $this->getPostParam('project');
        // the tab param.
        $this->tab_name = $this->getPostParam('tab');
    }

    /**
     * create request context based on the page url.
     */
    public function createContext() {

        $context = new RequestContext();
        if(empty($this->project_name)) {
            // all projects page
            $context = new AllProjectsRequestContext();
        }

        return $context;
    }

    /**
     * get a POST parameter's value, mainly from the $_POST
     */
    public function getPostParam($param) {

        // try to find the selected theme name
        if (array_key_exists($param, $_POST)) {
            $value = $_POST[$param];
        } else {
            $value = '';
        }

        return $value;
    }
}
