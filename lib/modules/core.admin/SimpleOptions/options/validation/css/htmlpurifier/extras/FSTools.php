<?php

/**
 * Filesystem tools not provided by default; can recursively create, copy
 * and delete folders. Some template methods are provided for extensibility.
 *
 * @note This class must be instantiated to be used, although it does
 *       not maintain state.
 */
class FSTools
{

    private static $singleton;

    /**
     * Returns a global instance of FSTools
     */
    static public function singleton() {
        if (empty(FSTools::$singleton)) FSTools::$singleton = new FSTools();
        return FSTools::$singleton;
    }

    /**
     * Sets our global singleton to something else; useful for overloading
     * functions.
     */
    static public function setSingleton($singleton) {
        FSTools::$singleton = $singleton;
    }

    /**
     * Recursively creates a directory
     * @param string $folder Name of folder to create
     * @note Adapted from the PHP manual comment 76612
     */
    public function mkdirr($folder) {
        $folders = preg_split("#[\\\\/]#", $folder);
        $base = '';
        for($i = 0, $c = count($folders); $i < $c; $i++) {
            if(empty($folders[$i])) {
                if (!$i) {
                    // special case for root level
                    $base .= DIRECTORY_SEPARATOR;
                }
                continue;
            }
            $base .= $folders[$i];
            if(!is_dir($base)){
                $this->mkdir($base);
            }
            $base .= DIRECTORY_SEPARATOR;
        }
    }

    /**
     * Copy a file, or recursively copy a folder and its contents; modified
     * so that copied files, if PHP, have includes removed
     * @note Adapted from http://aidanlister.com/repos/v/function.copyr.php
     */
    public function copyr($source, $dest) {
        // Simple copy for a file
        if (is_file($source)) {
            return $this->copy($source, $dest);
        }
        // Make destination directory
        if (!is_dir($dest)) {
            $this->mkdir($dest);
        }
        // Loop through the folder
        $dir = $this->dir($source);
        while ( false !== ($entry = $dir->read()) ) {
            // Skip pointers
            if ($entry == '.' || $entry == '..') {
                continue;
            }
            if (!$this->copyable($entry)) {
                continue;
            }
            // Deep copy directories
            if ($dest !== "$source/$entry") {
                $this->copyr("$source/$entry", "$dest/$entry");
            }
        }
        // Clean up
        $dir->close();
        return true;
    }

    /**
     * Overloadable function that tests a filename for copyability. By
     * default, everything should be copied; you can restrict things to
     * ignore hidden files, unreadable files, etc. This function
     * applies to copyr().
     */
    public function copyable($file) {
        return true;
    }

    /**
     * Delete a file, or a folder and its contents
     * @note Adapted from http://aidanlister.com/repos/v/function.rmdirr.php
     */
    public function rmdirr($dirname)
    {
        // Sanity check
        if (!$this->file_exists($dirname)) {
            return false;
        }

        // Simple delete for a file
        if ($this->is_file($dirname) || $this->is_link($dirname)) {
            return $this->unlink($dirname);
        }

        // Loop through the folder
        $dir = $this->dir($dirname);
        while (false !== $entry = $dir->read()) {
            // Skip pointers
            if ($entry == '.' || $entry == '..') {
                continue;
            }
            // Recurse
            $this->rmdirr($dirname . DIRECTORY_SEPARATOR . $entry);
        }

        // Clean up
        $dir->close();
        return $this->rmdir($dirname);
    }

    /**
     * Recursively globs a directory.
     */
    public function globr($dir, $pattern, $flags = NULL) {
        $files = $this->glob("$dir/$pattern", $flags);
        if ($files === false) $files = array();
        $sub_dirs = $this->glob("$dir/*", GLOB_ONLYDIR);
        if ($sub_dirs === false) $sub_dirs = array();
        foreach ($sub_dirs as $sub_dir) {
            $sub_files = $this->globr($sub_dir, $pattern, $flags);
            $files = array_merge($files, $sub_files);
        }
        return $files;
    }

    /**
     * Allows for PHP functions to be called and be stubbed.
     * @warning This function will not work for functions that need
     *      to pass references; manually define a stub function for those.
     */
    public function __call($name, $args) {
        return call_user_func_array($name, $args);
    }

}

// vim: et sw=4 sts=4
