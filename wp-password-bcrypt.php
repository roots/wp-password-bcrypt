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

function wp_check_password($password, $hash, $user_id = '')
{
    if (substr($hash, 0, 3) === WP_OLD_HASH_PREFIX) {
        global $wp_hasher;

        if (empty($wp_hasher)) {
            require_once(ABSPATH . WPINC . '/class-phpass.php');

            $wp_hasher = new PasswordHash(8, true);
        }

        $check = $wp_hasher->CheckPassword($password, $hash);

        if ($check && $user_id) {
            $hash = wp_set_password($password, $user_id);
        }
    }

    $check = password_verify($password, $hash);
    return apply_filters('check_password', $check, $password, $hash, $user_id);
}

function wp_hash_password($password)
{
    $options = apply_filters('wp_hash_password_options', []);
    return password_hash($password, PASSWORD_DEFAULT, $options);
}

function wp_set_password($password, $user_id)
{
    global $wpdb;
    $hash = wp_hash_password($password);

    $wpdb->update($wpdb->users, ['user_pass' => $hash, 'user_activation_key' => ''], ['ID' => $user_id]);
    wp_cache_delete($user_id, 'users');

    return $hash;
}
