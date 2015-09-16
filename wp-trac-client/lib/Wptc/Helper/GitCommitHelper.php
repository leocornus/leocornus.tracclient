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
     * return the custom format for a commit.
     * git command:
     * $ git log -1 --pretty=format:"%ad-%h" --date=short commitid
     */
    public function getCustomFormat($format) {

        $git_cmd = "git log -1 {$format} " . $this->commit_id;
        chdir($this->repo_path);
        $log = explode("\n", shell_exec($git_cmd));

        return $log[0];
    }

    /**
     * return the full path to the archive zip file.
     */
    public function getArchivePath() {

        $base = basename($this->repo_path);
        // get ready format [COMMIT DATE]-[COMMIT ID]
        // git command:
        // $ git log -1 --pretty=format:"%ad-%h" --date=short cmitid
        $format = '--date=short --pretty=format:"%ad-%h"';
        $name = $this->getCustomFormat($format);

        // the archive folder?
        // TODO: this should be configurable.
        $archive_folder = "/tmp";

        $archive_path = "{$archive_folder}/{$base}-{$name}.zip";
        return $archive_path;
    }

    /**
     * using the archive to generate the zip file for a commit.
     * 
     * $ cd parent
     * $ git archive -o archive-name.zip [COMMIT ID] base
     */
    public function getArchive() {

        // get the dirname and basename for the repo path.
        $parent = dirname($this->repo_path);
        $base = basename($this->repo_path);
        // this will the full path!
        $zipfile = $this->getArchivePath();

        if (!file_exists($zipfile)) {
            // archive the file.
            $git_cmd = "git archive -o {$zipfile} {$this->commit_id} {$base}";
            chdir($parent);
            shell_exec($git_cmd);
        }

        return $zipfile;
    }
}
