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
     * if the project name is null, we will return the 
     * properties for root
     */
    public function __construct($project_name=null) {

        $this->project_name = $project_name;
    }

    /**
     * return total number of projects.
     */
    public function getAllProjectsTotal() {

        // pass no param, it will reall all projects.
        $projects = wptc_get_projects();
        $total = count($projects);

        // intorduce the filter hook.
        if(has_filter('wptc_all_projects_total')) {
            $total = apply_filters('wptc_all_projects_total',
                                   $total);
        }

        return $total;
    }

    /**
     * return total number of all tickets.
     */
    public function getAllTicketsTotal() {

        // set query to null to return all tickets.
        $ids = wptc_ticket_query(null, 0);
        $total = count($ids);

        // intorduce the filter hook.
        if(has_filter('wptc_all_tickets_total')) {
            $total = apply_filters('wptc_all_tickets_total',
                                   $total);
        }

        return $total;
    }

    public function getAllCommitsTotal() {

        //
        if(function_exists(wpg_get_log_count)) {
            $total = wpg_get_log_count(getcwd());
        }

        // intorduce the filter hook.
        if(has_filter('wptc_all_commits_total')) {
            $total = apply_filters('wptc_all_commits_total',
                                   $total);
        }

        return $total;
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

    /**
     * return all repo bathes for this project.
     * one project may have more than one repositories.
     */
    public function getRepoPathes() {

        $pathes = [];
        // introduce the filter for user to tweak.
        if (has_filter('wptc_project_repo_pathes')) {
            $pathes = apply_filters('wptc_project_repo_pathes',
                                    $pathes, $this->project_name);
        }

        return $pathes;
    }
}
