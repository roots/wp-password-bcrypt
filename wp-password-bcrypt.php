<?php
/**
 * Plugin Name: WP Password bcrypt
 * Plugin URI:  https://roots.io
 * Description: Replaces wp_hash_password and wp_check_password with PHP 5.5's password_hash and password_verify.
 * Author:      Roots
 * Author URI:  https://roots.io
 * Version:     1.0
 * Licence:     MIT
 */

const WP_OLD_HASH_PREFIX = '$P$';

/**
 * Check if user has entered correct password, supports bcrypt and pHash.
 *
 * @param string $password Plaintext password
 * @param string $hash Hash of password
 * @param int|string $user_id User ID
 * @return mixed|void
 *
 * @SuppressWarnings(PHPMD.CamelCaseVariableName) $wp_hasher is a global variable, we cannot change its name
 */
function wp_check_password( $password, $hash, $user_id = '' ) {
	$check = password_verify( $password, $hash );

	if ( ! $check && strpos( $hash, WP_OLD_HASH_PREFIX ) === 0 ) {
		global $wp_hasher;

		if ( empty( $wp_hasher ) ) {
			require_once( ABSPATH . WPINC . '/class-phpass.php' );

			$wp_hasher = new PasswordHash( 8, true );
		}

		$check = $wp_hasher->CheckPassword( $password, $hash );
	}

	if ( $check && $user_id ) {
		$cost = apply_filters( 'wp_hash_password_cost', 10 );

		if( password_needs_rehash( $hash, PASSWORD_DEFAULT, [ 'cost' => $cost ] ) ) {
			$hash = wp_set_password( $password, $user_id );
		}
	}

	return apply_filters( 'check_password', $check, $password, $hash, $user_id );
}

/**
 * Hash password using bcrypt
 *
 * @param string $password Plaintext password
 * @return bool|string
 */
function wp_hash_password( $password ) {
	$cost = apply_filters( 'wp_hash_password_cost', 10 );

	return password_hash( $password, PASSWORD_DEFAULT, [ 'cost' => $cost ] );
}

/**
 * Set password using bcrypt
 *
 * @param string $password Plaintext password
 * @param int $user_id User ID
 * @return bool|string
 */
function wp_set_password( $password, $user_id ) {
	/** @var \wpdb $wpdb */
	global $wpdb;

	$hash = wp_hash_password( $password );

	$wpdb->update(
		$wpdb->users,
		[
			'user_pass'           => $hash,
			'user_activation_key' => '',
		],
		[
			'ID' => $user_id,
		]
	);

	wp_cache_delete( $user_id, 'users' );

	return $hash;
}
