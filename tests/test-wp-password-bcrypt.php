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
	const PASSWORD = 'password';
	const HASH_BCRYPT = '$2y$10$KIMXDMJq9camkaNHkdrmcOaYJ0AT9lvovEf92yWA34sKdfnn97F9i';
	const HASH_PORTABLE = '$P$BDMJH/qCLaUc5Lj8Oiwp7XmWzrCcJ21';
	const HASH_PHPBB = '$H$BDMJH/qCLaUc5Lj8Oiwp7XmWzrCcJ21';
	const HASH_MD5 = '5f4dcc3b5aa765d61d8327deb882cf99';

	/**
	 * Check that constants is defined.
	 */
	function test_constant_defined() {
		$this->assertTrue( defined( 'PASSWORD_BCRYPT' ) );
		$this->assertTrue( defined( 'PASSWORD_DEFAULT' ) );
		$this->assertTrue( defined( 'PASSWORD_BCRYPT_DEFAULT_COST' ) );
	}

	/**
	 * Test wp_check_password() behavior.
	 */
	function test_wp_check_password() {
		$this->assertSame( wp_check_password( self::PASSWORD, self::HASH_BCRYPT ) );
		$this->assertSame( wp_check_password( self::PASSWORD, self::PORTABLE ) );
		$this->assertSame( wp_check_password( self::PASSWORD, self::PHPBB ) );
		$this->assertSame( wp_check_password( self::PASSWORD, self::MD5 ) );
	}
}
