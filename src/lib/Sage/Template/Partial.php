<?php

namespace Roots\Sage\Template;

class Partial
{
    protected static $cache = [];

    public $main;
    public $template;
    public $delimiter = '-';

    public function __construct($template, $main = '')
    {
        $this->template = $template;
        $this->main = $main;
    }

    public function __toString()
    {
        return (string) $this->path();
    }

    /**
     * Converts template into array of parts to be passed to locate_template()
     *
     * Here's an example of what happens:
     *   (new Template('partials/content-single-audio'))->parts();
     *   // => ['partials/content-single-audio.php', 'partials/content-single.php', 'partials/content.php']
     * @return array Array of parts to pass to locate_template()
     */
    public function parts()
    {
        if ($parts = $this->cache('parts')) {
            return $parts;
        }
        $parts = explode($this->delimiter, str_replace('.php', '', $this->template));
        $templates[] = array_shift($parts);
        foreach ($parts as $i => $part) {
            $templates[] = $templates[$i] . $this->delimiter . $part;
        }
        if ($this->main) {
            $templates = array_merge($templates, array_map(function ($template) {
                return $template . $this->delimiter . basename($this->main, '.php');
            }, $templates));
        }
        $templates = array_map(function ($template) {
            return $template . '.php';
        }, $templates);
        return $this->cache('parts', array_reverse($templates));
    }

    /**
     * Passes $this->parts() to locate_template() to retrieve template location
     * @return string Location of template
     */
    public function path()
    {
        if (!$path = $this->cache('path')) {
            $path = $this->cache('path', locate_template($this->parts()));
        }
        return apply_filters('sage/partial_' . basename($path, '.php'), $path, $this->parts()) ?: $path;
    }

    protected function cache($key, $value = null)
    {
        if ($value !== null) {
            self::$cache[$this->template][$key] = $value;
        }
        return isset(self::$cache[$this->template][$key]) ? self::$cache[$this->template][$key] : null;
    }
}
