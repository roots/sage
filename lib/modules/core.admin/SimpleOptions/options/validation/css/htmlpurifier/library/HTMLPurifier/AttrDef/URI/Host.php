<?php

/**
 * Validates a host according to the IPv4, IPv6 and DNS (future) specifications.
 */
class HTMLPurifier_AttrDef_URI_Host extends HTMLPurifier_AttrDef
{

    /**
     * Instance of HTMLPurifier_AttrDef_URI_IPv4 sub-validator
     */
    protected $ipv4;

    /**
     * Instance of HTMLPurifier_AttrDef_URI_IPv6 sub-validator
     */
    protected $ipv6;

    public function __construct() {
        $this->ipv4 = new HTMLPurifier_AttrDef_URI_IPv4();
        $this->ipv6 = new HTMLPurifier_AttrDef_URI_IPv6();
    }

    public function validate($string, $config, $context) {
        $length = strlen($string);
        // empty hostname is OK; it's usually semantically equivalent:
        // the default host as defined by a URI scheme is used:
        //
        //      If the URI scheme defines a default for host, then that
        //      default applies when the host subcomponent is undefined
        //      or when the registered name is empty (zero length).
        if ($string === '') return '';
        if ($length > 1 && $string[0] === '[' && $string[$length-1] === ']') {
            //IPv6
            $ip = substr($string, 1, $length - 2);
            $valid = $this->ipv6->validate($ip, $config, $context);
            if ($valid === false) return false;
            return '['. $valid . ']';
        }

        // need to do checks on unusual encodings too
        $ipv4 = $this->ipv4->validate($string, $config, $context);
        if ($ipv4 !== false) return $ipv4;

        // A regular domain name.

        // This doesn't match I18N domain names, but we don't have proper IRI support,
        // so force users to insert Punycode.

        // The productions describing this are:
        $a   = '[a-z]';     // alpha
        $an  = '[a-z0-9]';  // alphanum
        $and = '[a-z0-9-]'; // alphanum | "-"
        // domainlabel = alphanum | alphanum *( alphanum | "-" ) alphanum
        $domainlabel   = "$an($and*$an)?";
        // toplabel    = alpha | alpha *( alphanum | "-" ) alphanum
        $toplabel      = "$a($and*$an)?";
        // hostname    = *( domainlabel "." ) toplabel [ "." ]
        if (preg_match("/^($domainlabel\.)*$toplabel\.?$/i", $string)) {
            return $string;
        }

        // If we have Net_IDNA2 support, we can support IRIs by
        // punycoding them. (This is the most portable thing to do,
        // since otherwise we have to assume browsers support

        if ($config->get('Core.EnableIDNA')) {
            $idna = new Net_IDNA2(array('encoding' => 'utf8', 'overlong' => false, 'strict' => true));
            // we need to encode each period separately
            $parts = explode('.', $string);
            try {
                $new_parts = array();
                foreach ($parts as $part) {
                    $encodable = false;
                    for ($i = 0, $c = strlen($part); $i < $c; $i++) {
                        if (ord($part[$i]) > 0x7a) {
                            $encodable = true;
                            break;
                        }
                    }
                    if (!$encodable) {
                        $new_parts[] = $part;
                    } else {
                        $new_parts[] = $idna->encode($part);
                    }
                }
                $string = implode('.', $new_parts);
                if (preg_match("/^($domainlabel\.)*$toplabel\.?$/i", $string)) {
                    return $string;
                }
            } catch (Exception $e) {
                // XXX error reporting
            }
        }

        return false;
    }

}

// vim: et sw=4 sts=4
