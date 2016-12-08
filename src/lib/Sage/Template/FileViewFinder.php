<?php

namespace Roots\Sage\Template;

class FileViewFinder extends \Illuminate\View\FileViewFinder
{

    public $possible_parts_delimiter = '-';

    /**
     * Get an array of possible view files.
     *
     * @param  string  $name
     * @return array
     */
    protected function getPossibleViewFiles($name)
    {
        $parts = explode($this->possible_parts_delimiter, $name);
        $templates[] = array_shift($parts);
        foreach ($parts as $i => $part) {
            $templates[] = $templates[$i].$this->possible_parts_delimiter.$part;
        }
        rsort($templates);
        return call_user_func_array('array_merge', array_map(function ($template) {
            return array_map(function ($extension) use ($template) {
                return str_replace('.', '/', $template).'.'.$extension;
            }, $this->extensions);
        }, $templates));
    }
}
