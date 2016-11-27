<?php

namespace Genero\Sage;

use Twig_Extension;
use Twig_Filter_Method;

/**
 * Provide a `linkify` filter which transforms URL addresses to HTML links.
 * @example
 * {{footnote|linkify}}
 */
class Twig_Extension_Linkify extends Twig_Extension
{
    public function getFilters() {
        return [
            'linkify' => new Twig_Filter_Method($this, 'linkify', [
                'is_safe' => ['html'],
            ]),
        ];
    }

    public function getName() {
        return 'twig_extension_linkify';
    }

    static public function linkify($string) {
        $regexp = "/(<a.*?>)?(https?)(:\/\/)(\w+\.)?(\w+)\.(\w+)([^ ]*)?(<\/a.*?>)?/i";
        $anchorMarkup = "<a href=\"%s://%s\" target=\"_blank\" >%s</a>";
        preg_match_all($regexp, $string, $matches, \PREG_SET_ORDER);
        foreach ($matches as $match) {
            if (empty($match[1]) && empty($match[8])) {
                $http = $match[2] ? $match[2] : 'http';
                $replace = sprintf($anchorMarkup, $http, $match[0], $match[0]);
                $string = str_replace($match[0], $replace, $string);
            }
        }
        return $string;
    }
}
