<?php
/**
 * Class SampleTest
 *
 * @package Sage
 */

require_once dirname(__FILE__).'/../../app/helpers.php';

/**
 * Sample test case.
 */
class SampleTest extends WP_UnitTestCase {

    /**
     * A single example test.
     */
    function test_theme_name() {
        $this->assertEquals('Sage', wp_get_theme());
    }
}
