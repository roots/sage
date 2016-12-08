<?php

namespace Roots\Sage\Template;

class FileViewFinder extends \Illuminate\View\FileViewFinder
{
    const FALLBACK_PARTS_DELIMITER = '-';

    /**
     * Get an array of possible view files.
     *
     * @param  string  $name
     * @return array
     */
    protected function getPossibleViewFiles($name)
    {
        $parts = explode(self::FALLBACK_PARTS_DELIMITER, $name);
        $templates[] = array_shift($parts);
        foreach ($parts as $i => $part) {
            $templates[] = $templates[$i].self::FALLBACK_PARTS_DELIMITER.$part;
        }
        rsort($templates);
        return call_user_func_array('array_merge', array_map(function ($template) {
            return array_map(function ($extension) use ($template) {
                return str_replace('.', '/', $template).'.'.$extension;
            }, $this->extensions);
        }, $templates));
    }
}
