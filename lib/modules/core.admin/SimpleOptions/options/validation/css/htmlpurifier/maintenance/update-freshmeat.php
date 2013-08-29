#!/usr/bin/php
<?php

chdir(dirname(__FILE__));
require_once 'common.php';
assertCli();

/**
 * @file
 * Updates Freshmeat's HTML Purifier with the latest information via XML RPC.
 */

class XmlRpc_Freshmeat
{

    const URL = 'http://freshmeat.net/xmlrpc/';

    public $chatty = false;

    public $encodeOptions = array(
        'encoding' => 'utf-8',
    );

    /**
     * This array defines shortcut method signatures for dealing with simple
     * XML RPC methods. More complex ones (publish_release) should use the named parameter
     * syntax.
     */
    public $signatures = array(
        'login' => array('username', 'password'),
        'fetch_branch_list' => array('project_name'),
        'fetch_release'    => array('project_name', 'branch_name', 'version'),
        'withdraw_release' => array('project_name', 'branch_name', 'version'),
    );

    protected $sid = null;

    /**
     * @param $username Username to login with
     * @param $password Password to login with
     */
    public function __construct($username = null, $password = null) {
        if ($username && $password) {
            $this->login($username, $password);
        }
    }

    /**
     * Performs a raw XML RPC call to self::URL
     */
    protected function call($method, $params) {
        $request = xmlrpc_encode_request($method, $params, $this->encodeOptions);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-type: text/xml',
            'Content-length: ' . strlen($request)
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        $data = curl_exec($ch);
        if ($errno = curl_errno($ch)) {
            throw new Exception("Curl error [$errno]: " . curl_error($ch));
        } else {
            curl_close($ch);
            return xmlrpc_decode($data);
        }
    }

    /**
     * Performs an XML RPC call to Freshmeat.
     * @param $name Name of method to call, can be methodName or method_name
     * @param $args Arguments of call, in form array('key1', 'val1', 'key2' ...)
     */
    public function __call($name, $args) {
        $method = $this->camelToUnderscore($name);
        $params = array();
        if ($this->sid) $params['SID'] = $this->sid;
        if (isset($this->signatures[$method])) {
            for ($i = 0, $c = count($this->signatures[$method]); $i < $c; $i++) {
                $params[$this->signatures[$method][$i]] = $args[$i];
            }
        } else {
            for ($i = 0, $c = count($args); $i + 1 < $c; $i += 2) {
                $params[$args[$i]] = $args[$i + 1];
            }
        }
        $result = $this->call($method, $params);
        switch ($method) {
            case 'login':
                $this->sid = $result['SID'];
                break;
            case 'logout':
                $this->sid = null;
                break;
        }
        if ($this->chatty) print_r($result);
        return $result;
    }

    /**
     * Munge methodName to method_name
     */
    private function camelToUnderscore($name) {
        $method = '';
        for ($i = 0, $c = strlen($name); $i < $c; $i++) {
            $v = $name[$i];
            if (ctype_lower($v)) $method .= $v;
            else $method .= '_' . strtolower($v);
        }
        return $method;
    }

    /**
     * Automatically logout at end of scope
     */
    public function __destruct() {
        if ($this->sid) $this->logout();
    }

}

$rpc = new XmlRpc_Freshmeat($argv[1], $argv[2]);
$rpc->chatty = true;

$project = 'htmlpurifier';
$branch  = 'Default';
$version = file_get_contents('../VERSION');

$result = $rpc->fetchRelease($project, $branch, $version);
if (!isset($result['faultCode'])) {
    echo "Freshmeat release already exists.\n";
    exit(0);
}

$changes = strtr(file_get_contents('../WHATSNEW'), array("\r" => '', "\n" => ' '));
$focus = (int) trim(file_get_contents('../FOCUS'));

if (strlen($changes) > 600) {
    echo "WHATSNEW entry is too long.\n";
    exit(1);
}

$rpc->publishRelease(
    'project_name',  $project,
    'branch_name',   $branch,
    'version',       $version,
    'changes',       $changes,
    'release_focus', $focus,
    'url_tgz',       "http://htmlpurifier.org/releases/htmlpurifier-$version.tar.gz",
    'url_zip',       "http://htmlpurifier.org/releases/htmlpurifier-$version.zip",
    'url_changelog', "http://htmlpurifier.org/svnroot/htmlpurifier/tags/$version/NEWS"
);

// vim: et sw=4 sts=4
