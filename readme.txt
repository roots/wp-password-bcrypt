=== WP Password Bcrypt ===
Contributors: extendwings
Donate link: http://www.extendwings.com/donate/
Tags: security, hash, bcrypt, password
Requires at least: 4.4.2
Tested up to: 4.5-beta2
Stable tag: 1.1
License: MIT License
License URI: https://opensource.org/licenses/MIT

Enhance your login security with bcrypt.

== Description ==
WordPress is using the insecure method, MD5, to process and store your password. This plugin replaces it with highly secure one.

This plugin requires PHP 5.4 or greater for security reasons.

### License
* Copyright (c) 2012-2016 [Daisuke Takahashi (Extend Wings)](https://www.extendwings.com/)
* Portions (c) [Roots](https://github.com/roots/wp-password-bcrypt).
* Licensed under *MIT License*. See *LICENSE.md* file.

== Installation ==

1. Upload *the contents* of the `wp-password-bcrypt` folder to the `/wp-content/mu-plugins/` directory.

== Frequently Asked Questions ==

= This plugin is broken! Thanks for nothing! =

First of all, we supports PHP 5.4+, MySQL 5.6+, WordPress 4.4+. Old software is not supported.
If you're in supported environment, please create [pull request](https://github.com/shield-9/wp-password-bcrypt/compare/) or [issue](https://github.com/shield-9/wp-password-bcrypt/issues/new).

= What happens to existing passwords when I install the plugin? =

Nothing at first. An existing password is only re-hashed *when they log in*. If a user never logs in, their password will remain hashed with insecure method in your database forever.

= What happens if I remove/deactivate the plugin? =

If you are using PHP 5.5+, it's no problem. Everything works. But if you are using older PHP, you may need to reset password.

= What's wrong with using this as a plugin instead of a must-use plugin? =

As explained above, you don't want to disable this plugin once you've enabled it. Installing this in `plugins` (as a "normal" plugin) instead of in `mu-plugins` (as a must-use plugin) makes it possible for an admin user to accidentally disable it.

== Screenshots ==

No screen.

== Changelog ==

= 1.1 =
* Enhancement: Ready to future changes of PHP default hash algorithm. ([http://php.net/password_needs_rehash password_needs_rehash()])
* Enhancement: Supports phpBB-style hash and plain MD5.
* Enhancement: Supports PHP 5.4, thanks to [https://github.com/ircmaxell/password_compat/ ircmaxell/password_compat].

= 1.0 =
* Initial Release by Roots team on GitHub.

== Upgrade Notice ==

= 1.0 =
None.
