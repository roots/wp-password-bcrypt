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
 * @param int|string $userId ID of user to whom password belongs
 * @return mixed|void
 *
 * @SuppressWarnings(PHPMD.CamelCaseVariableName) $wp_hasher is a global variable, we cannot change its name
 */
function wp_check_password($password, $hash, $userId = '')
{
    if (strpos($hash, WP_OLD_HASH_PREFIX) === 0) {
        global $wp_hasher;

        if (empty($wp_hasher)) {
            require_once(ABSPATH . WPINC . '/class-phpass.php');

            $wp_hasher = new PasswordHash(8, true);
        }

        $check = $wp_hasher->CheckPassword($password, $hash);

        if ($check && $userId) {
            $hash = wp_set_password($password, $userId);
        }
    }

    $check = password_verify($password, $hash);
    return apply_filters('check_password', $check, $password, $hash, $userId);
}

/**
 * Hash password using bcrypt
 *
 * @param string $password Plaintext password
 * @return bool|string
 */
function wp_hash_password($password)
{
    $options = apply_filters('wp_hash_password_options', []);
    return password_hash($password, PASSWORD_DEFAULT, $options);
}

/**
 * Set password using bcrypt
 *
 * @param string $password Plaintext password
 * @param int $userId ID of user to whom password belongs
 * @return bool|string
 */
function wp_set_password($password, $userId)
{
    /** @var \wpdb $wpdb */
    global $wpdb;

    $hash = wp_hash_password($password);

    $wpdb->update($wpdb->users, ['user_pass' => $hash, 'user_activation_key' => ''], ['ID' => $userId]);
    wp_cache_delete($userId, 'users');

    return $hash;
}
