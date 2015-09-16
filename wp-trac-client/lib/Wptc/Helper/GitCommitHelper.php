<?php
/**
 * The Wptc helper class to manipulate git commits.
 */
namespace Wptc\Helper;

/**
 * comprehensive utils for git commits..
 */
class GitCommitHelper {

    /**
     * constructor.
     */
    public function __construct($repo_path, $commit_id) {

        $this->repo_path = $repo_path;
        $this->commit_id = $commit_id;
    }

    /**
     * return the download format for a commit.
     * [COMMIT DATE]-[COMMIT ID]
     * git command:
     * $ git log -1 --pretty=format:"%ad-%h" --date=short commitid
     */
    public function getDownloadFormat() {

        $format = '--date=short --pretty=format:"%ad-%h"';
        $git_cmd = "git log -1 {$format} " . $this->commit_id;
        chdir($this->repo_path);
        $log = explode("\n", shell_exec($git_cmd));

        return $log[0];
    }

    /**
     *
     */
    public function getArchive() {
    }
}
