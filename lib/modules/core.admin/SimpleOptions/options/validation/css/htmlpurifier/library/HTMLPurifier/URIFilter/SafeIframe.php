<?php

/**
 * Implements safety checks for safe iframes.
 *
 * @warning This filter is *critical* for ensuring that %HTML.SafeIframe
 * works safely.
 */
class HTMLPurifier_URIFilter_SafeIframe extends HTMLPurifier_URIFilter
{
    public $name = 'SafeIframe';
    public $always_load = true;
    protected $regexp = NULL;
    // XXX: The not so good bit about how this is all setup now is we
    // can't check HTML.SafeIframe in the 'prepare' step: we have to
    // defer till the actual filtering.
    public function prepare($config) {
        $this->regexp = $config->get('URI.SafeIframeRegexp');
        return true;
    }
    public function filter(&$uri, $config, $context) {
        // check if filter not applicable
        if (!$config->get('HTML.SafeIframe')) return true;
        // check if the filter should actually trigger
        if (!$context->get('EmbeddedURI', true)) return true;
        $token = $context->get('CurrentToken', true);
        if (!($token && $token->name == 'iframe')) return true;
        // check if we actually have some whitelists enabled
        if ($this->regexp === null) return false;
        // actually check the whitelists
        return preg_match($this->regexp, $uri->toString());
    }
}

// vim: et sw=4 sts=4
