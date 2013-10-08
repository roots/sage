<?php

/**
 * Tests to test that that testing framework is testing tests. Meta, huh?
 *
 * @package wordpress-plugins-tests
 */
class WP_Test_WordPress_Plugin_Tests extends WP_UnitTestCase {

function test_kickoff() {
	$this->assertTrue( true );
}

function test_fail() {
	$this->assertTrue( false );
}
	/**
	 * Run a simple test to ensure that the tests are running
	 */
	 function test_tests() {

		$this->assertTrue( true );

	 }

	/**
	 * If these tests are being run on Travis CI, verify that the version of
	 * WordPress installed is the version that we requested.
	 */
	function test_wp_version() {
		
		if ( !getenv( 'TRAVIS_PHP_VERSION' ) )
			$this->markTestSkipped( 'Test skipped since Travis CI was not detected.' );

		//grab the requested version
		$requested_version = getenv( 'WP_VERSION' );

		//trunk is always "master" in github terms, but WordPress has a specific way of describing it
		//grab the exact version number to verify that we're on trunk
		if ( $requested_version == 'master' ) {
			$file = file_get_contents( 'https://raw.github.com/WordPress/WordPress/master/wp-includes/version.php' );
			preg_match( '#\$wp_version = \'([^\']+)\';#', $file, $matches );
			$requested_version = $matches[1];
		}

		$this->assertEquals( get_bloginfo( 'version' ), $requested_version );

	}

}
