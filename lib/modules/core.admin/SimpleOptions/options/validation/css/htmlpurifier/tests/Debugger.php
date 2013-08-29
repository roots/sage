<?php

/**
 * Debugging tools.
 *
 * This file gives a developer a set of tools useful for performing code
 * consistency checks. This includes scoping code blocks to ensure that
 * only the interesting iteration of a loop gets outputted, a paint()
 * function that acts like var_dump() with pre tags, and conditional
 * printing.
 */

/*
TODO
 * Integrate into SimpleTest so it tells us whether or not there were any
   not cleaned up debug calls.
 * Custom var_dump() that ignores blacklisted properties
 * DEPRECATE AND REMOVE ALL CALLS!
*/

/**#@+
 * Convenience global functions. Corresponds to method on Debugger.
 */
function paint($mixed) {
    $Debugger =& Debugger::instance();
    return $Debugger->paint($mixed);
}
function paintIf($mixed, $conditional) {
    $Debugger =& Debugger::instance();
    return $Debugger->paintIf($mixed, $conditional);
}
function paintWhen($mixed, $scopes = array()) {
    $Debugger =& Debugger::instance();
    return $Debugger->paintWhen($mixed, $scopes);
}
function paintIfWhen($mixed, $conditional, $scopes = array()) {
    $Debugger =& Debugger::instance();
    return $Debugger->paintIfWhen($mixed, $conditional, $scopes);
}
function addScope($id = false) {
    $Debugger =& Debugger::instance();
    return $Debugger->addScope($id);
}
function removeScope($id) {
    $Debugger =& Debugger::instance();
    return $Debugger->removeScope($id);
}
function resetScopes() {
    $Debugger =& Debugger::instance();
    return $Debugger->resetScopes();
}
function isInScopes($array = array()) {
    $Debugger =& Debugger::instance();
    return $Debugger->isInScopes($array);
}
/**#@-*/


/**
 * The debugging singleton. Most interesting stuff happens here.
 */
class Debugger
{

    public $shouldPaint = false;
    public $paints  = 0;
    public $current_scopes = array();
    public $scope_nextID = 1;
    public $add_pre = true;

    public function Debugger() {
        $this->add_pre = !extension_loaded('xdebug');
    }

    public static function &instance() {
        static $soleInstance = false;
        if (!$soleInstance) $soleInstance = new Debugger();
        return $soleInstance;
    }

    public function paintIf($mixed, $conditional)  {
        if (!$conditional) return;
        $this->paint($mixed);
    }

    public function paintWhen($mixed, $scopes = array()) {
        if (!$this->isInScopes($scopes)) return;
        $this->paint($mixed);
    }

    public function paintIfWhen($mixed, $conditional, $scopes = array()) {
        if (!$conditional) return;
        if (!$this->isInScopes($scopes)) return;
        $this->paint($mixed);
    }

    public function paint($mixed) {
        $this->paints++;
        if($this->add_pre) echo '<pre>';
        var_dump($mixed);
        if($this->add_pre) echo '</pre>';
    }

    public function addScope($id = false) {
        if ($id == false) {
            $id = $this->scope_nextID++;
        }
        $this->current_scopes[$id] = true;
    }

    public function removeScope($id) {
        if (isset($this->current_scopes[$id])) unset($this->current_scopes[$id]);
    }

    public function resetScopes() {
        $this->current_scopes = array();
        $this->scope_nextID = 1;
    }

    public function isInScopes($scopes = array()) {
        if (empty($this->current_scopes)) {
            return false;
        }
        if (!is_array($scopes)) {
            $scopes = array($scopes);
        }
        foreach ($scopes as $scope_id) {
            if (empty($this->current_scopes[$scope_id])) {
                return false;
            }
        }
        if (empty($scopes)) {
            if ($this->scope_nextID == 1) {
                return false;
            }
            for($i = 1; $i < $this->scope_nextID; $i++) {
                if (empty($this->current_scopes[$i])) {
                    return false;
                }
            }
        }
        return true;
    }

}

// vim: et sw=4 sts=4
