<?php

class HTMLPurifier_SimpleTest_Reporter extends HTMLReporter
{

    protected $ac;

    public function __construct($encoding, $ac) {
        $this->ac = $ac;
        parent::__construct($encoding);
    }

    public function paintHeader($test_name) {
        parent::paintHeader($test_name);
?>
<form action="" method="get" id="select">
    <select name="f">
        <option value="" style="font-weight:bold;"<?php if(!$this->ac['file']) {echo ' selected';} ?>>All Tests</option>
        <?php foreach($GLOBALS['HTMLPurifierTest']['Files'] as $file) { ?>
            <option value="<?php echo $file ?>"<?php
                if ($this->ac['file'] == $file) echo ' selected';
            ?>><?php echo $file ?></option>
        <?php } ?>
    </select>
    <input type="checkbox" name="standalone" value="1" title="Standalone version?" <?php if($this->ac['standalone']) {echo 'checked="checked" ';} ?>/>
    <input type="submit" value="Go">
</form>
<?php
        flush();
    }

    public function paintFooter($test_name) {
        if (function_exists('xdebug_peak_memory_usage')) {
            $max_mem = number_format(xdebug_peak_memory_usage());
            echo "<div>Max memory usage: $max_mem bytes</div>";
        }
        parent::paintFooter($test_name);
    }

    protected function getCss() {
        $css = parent::getCss();
        $css .= '
        #select {position:absolute;top:0.2em;right:0.2em;}
        ';
        return $css;
    }

    function getTestList() {
        // hacky; depends on a specific implementation of paintPass, etc.
        $list = parent::getTestList();
        $testcase = $list[1];
        if (class_exists($testcase, false)) $file = str_replace('_', '/', $testcase) . '.php';
        else $file = $testcase;
        $list[1] = '<a href="index.php?file=' . $file . '">' . $testcase . '</a>';
        return $list;
    }

}

// vim: et sw=4 sts=4
