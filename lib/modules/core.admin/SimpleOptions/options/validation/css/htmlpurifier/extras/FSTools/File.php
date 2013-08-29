<?php

/**
 * Represents a file in the filesystem
 *
 * @warning Be sure to distinguish between get() and write() versus
 *      read() and put(), the former operates on the entire file, while
 *      the latter operates on a handle.
 */
class FSTools_File
{

    /** Filename of file this object represents */
    protected $name;

    /** Handle for the file */
    protected $handle = false;

    /** Instance of FSTools for interfacing with filesystem */
    protected $fs;

    /**
     * Filename of file you wish to instantiate.
     * @note This file need not exist
     */
    public function __construct($name, $fs = false) {
        $this->name = $name;
        $this->fs = $fs ? $fs : FSTools::singleton();
    }

    /** Returns the filename of the file. */
    public function getName() {return $this->name;}

    /** Returns directory of the file without trailing slash */
    public function getDirectory() {return $this->fs->dirname($this->name);}

    /**
     * Retrieves the contents of a file
     * @todo Throw an exception if file doesn't exist
     */
    public function get() {
        return $this->fs->file_get_contents($this->name);
    }

    /** Writes contents to a file, creates new file if necessary */
    public function write($contents) {
        return $this->fs->file_put_contents($this->name, $contents);
    }

    /** Deletes the file */
    public function delete() {
        return $this->fs->unlink($this->name);
    }

    /** Returns true if file exists and is a file. */
    public function exists() {
        return $this->fs->is_file($this->name);
    }

    /** Returns last file modification time */
    public function getMTime() {
        return $this->fs->filemtime($this->name);
    }

    /**
     * Chmod a file
     * @note We ignore errors because of some weird owner trickery due
     *       to SVN duality
     */
    public function chmod($octal_code) {
        return @$this->fs->chmod($this->name, $octal_code);
    }

    /** Opens file's handle */
    public function open($mode) {
        if ($this->handle) $this->close();
        $this->handle = $this->fs->fopen($this->name, $mode);
        return true;
    }

    /** Closes file's handle */
    public function close() {
        if (!$this->handle) return false;
        $status = $this->fs->fclose($this->handle);
        $this->handle = false;
        return $status;
    }

    /** Retrieves a line from an open file, with optional max length $length */
    public function getLine($length = null) {
        if (!$this->handle) $this->open('r');
        if ($length === null) return $this->fs->fgets($this->handle);
        else return $this->fs->fgets($this->handle, $length);
    }

    /** Retrieves a character from an open file */
    public function getChar() {
        if (!$this->handle) $this->open('r');
        return $this->fs->fgetc($this->handle);
    }

    /** Retrieves an $length bytes of data from an open data */
    public function read($length) {
        if (!$this->handle) $this->open('r');
        return $this->fs->fread($this->handle, $length);
    }

    /** Writes to an open file */
    public function put($string) {
        if (!$this->handle) $this->open('a');
        return $this->fs->fwrite($this->handle, $string);
    }

    /** Returns TRUE if the end of the file has been reached */
    public function eof() {
        if (!$this->handle) return true;
        return $this->fs->feof($this->handle);
    }

    public function __destruct() {
        if ($this->handle) $this->close();
    }

}

// vim: et sw=4 sts=4
