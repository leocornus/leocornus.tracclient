<?php
/**
 * The Wptc Project Helper class.
 */
namespace Wptc\Helper;

/**
 * wrap up a project.
 */
class ProjectHelper {

    /**
     * constructor.
     */
    public function __construct($project_name) {

        $this->project_name = $project_name;
    }

    /**
     * return total number of projects.
     */
    public function getAllProjectsTotal() {

        // pass no param, it will reall all projects.
        $projects = wptc_get_projects();

        return count($projects);
    }

    /**
     * return total number of all tickets.
     */
    public function getAllTicketsTotal() {

        // set query to null to return all tickets.
        $ids = wptc_ticket_query(null, 0);
        return count($ids);
    }

    /**
     * return the summary of total numbers.
     */
    public function getTotalSumary() {

        $summary = array(
            'total_tickets' => $this->getTicketTotal(),
            'total_contributors' => $this->getContributorTotal(),
            'total_commits' => $this->getCommitTotal()
        );

        return $summary;
    }

    /**
     * return total number of tickets.
     */
    public function getTicketTotal() {

        // get total number of tickets.
        $query = "project={$this->project_name}";
        // max set to 0 will retrun all match.
        $ids = wptc_ticket_query($query, 0);
        $total = count($ids);

        // intorduce the filter hook.
        if(has_filter('wptc_project_ticket_total')) {
            $total = apply_filters('wptc_project_ticket_total',
                                   0, $this->project_name);
        }

        return $total;
    }

    /**
     * return total contributor.
     */
    public function getContributorTotal() {

        $total = 0;

        // intorduce the filter hook.
        if(has_filter('wptc_project_contributor_total')) {
            $total = apply_filters('wptc_project_contributor_total',
                                   0, $this->project_name);
        }

        return $total;
    }

    /**
     * return total commit.
     */
    public function getCommitTotal() {

        $total = 0;

        // intorduce the filter hook.
        if(has_filter('wptc_project_commit_total')) {
            $total = apply_filters('wptc_project_commit_total',
                                   0, $this->project_name);
        }

        return $total;
    }
}
