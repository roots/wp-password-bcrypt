<?php
/**
 * Test WP Password bcrypt.
 *
 * @package WP_Password_Bcrypt
 */

/**
 * Testing class for WP Password bcrypt.
 *
 * @package WP_Password_Bcrypt
 */
class Tests_WP_Password_Bcrypt extends WP_UnitTestCase {
	/**
	 * Check that constants is defined.
	 */
	function test_constant_defined() {
		$this->assertTrue( defined( 'PASSWORD_BCRYPT' ) );
		$this->assertTrue( defined( 'PASSWORD_DEFAULT' ) );
		$this->assertTrue( defined( 'PASSWORD_BCRYPT_DEFAULT_COST' ) );
	}
}
