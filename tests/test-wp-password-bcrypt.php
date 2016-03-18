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
	 * Create and switch to dummy user.
	 */
	private function use_dummy_user() {
		$user = new WP_User( $this->factory->user->create() );
		$this->old_user_id = get_current_user_id();
		wp_set_current_user( $user->ID );

		return $user;
	}

	/**
	 * Switch back to previous user.
	 */
	private function use_previous_user() {
		wp_set_current_user( $this->old_user_id );

		return wp_get_current_user();
	}

	/**
	 * Check that constants is defined.
	 */
	function test_constant_defined() {
		$this->assertTrue( defined( 'PASSWORD_BCRYPT' ) );
		$this->assertTrue( defined( 'PASSWORD_DEFAULT' ) );
		$this->assertTrue( defined( 'PASSWORD_BCRYPT_DEFAULT_COST' ) );
	}

	/**
	 * Test wp_check_password() behavior without $user_id.
	 */
	function test_wp_check_password() {
		$this->assertTrue( wp_check_password( self::PASSWORD, self::HASH_BCRYPT ) );
		$this->assertTrue( wp_check_password( self::PASSWORD, self::HASH_PORTABLE ) );
		$this->assertTrue( wp_check_password( self::PASSWORD, self::HASH_PHPBB ) );
		$this->assertTrue( wp_check_password( self::PASSWORD, self::HASH_MD5 ) );
	}

	/**
	 * Test wp_hash_password() behavior.
	 */
	function test_wp_hash_password() {
		$hash = wp_hash_password( self::PASSWORD );

		$this->assertTrue( wp_check_password( self::PASSWORD, $hash ) );
	}

	/**
	 * Test wp_check_password() behavior with $user_id.
	 */
	function test_wp_check_password_rehash() {
		$user = $this->use_dummy_user();

		$this->assertTrue( wp_check_password( self::PASSWORD, self::HASH_BCRYPT, $user->ID ) );
		$this->assertTrue( wp_check_password( self::PASSWORD, self::HASH_PORTABLE, $user->ID ) );
		$this->assertTrue( wp_check_password( self::PASSWORD, self::HASH_PHPBB, $user->ID ) );
		$this->assertTrue( wp_check_password( self::PASSWORD, self::HASH_MD5, $user->ID ) );

		$user = $this->use_previous_user();
	}
}
