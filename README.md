# wp-password-bcrypt
wp-password-bcrypt is a WordPress plugin to replace WP's outdated and insecure MD5-based password hashing with the modern and secure [bcrypt](https://en.wikipedia.org/wiki/Bcrypt).

[![Build Status](https://travis-ci.org/shield-9/wp-password-bcrypt.svg?branch=master)](https://travis-ci.org/shield-9/wp-password-bcrypt) [![codecov.io](https://codecov.io/github/shield-9/wp-password-bcrypt/coverage.svg?branch=master)](https://codecov.io/github/shield-9/wp-password-bcrypt?branch=master) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/shield-9/wp-password-bcrypt/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/shield-9/wp-password-bcrypt/?branch=master)

This plugin requires PHP >= 5.4.0.

## Requirements

* PHP >= 5.4.0
* WordPress >= 4.4 (see https://core.trac.wordpress.org/ticket/33904)

## Installation

#### Manually as a must-use plugin

If you don't use Composer, you can manually copy `wp-password-bcrypt.php` into your `mu-plugins` folder.

We **do not** recommend using this as a normal (non-mu) plugin. It makes it too easy to disable or remove the plugin.

## The Problem

WordPress still uses an MD5 based password hashing scheme. They are effectively making 25% of websites more insecure because they refuse to bump their minimum PHP requirements. By continuing to allow EOL PHP versions back to 5.2, they can't use newer functions like `password_hash`.

This is a [known](https://core.trac.wordpress.org/ticket/21022) problem which WordPress has ignored for over 4 years now. Not only does WordPress set the insecure default of MD5, they don't do any of the following:

* document this issue
* provide instructions on how to fix it and make it more secure
* notify users on newer PHP versions that they *could* be more secure

What's wrong with MD5? Really simply: it's too cheap and fast to generate cryptographically secure hashes.

## The Solution

WordPress did at least one good thing: they made `wp_check_password` and `wp_hash_password` [pluggable](https://codex.wordpress.org/Pluggable_Functions) functions. This means we can define these functions in a plugin and "override" the default ones.

## FAQ

**What happens to existing passwords when I install the plugin?**

Nothing at first. An existing password is only re-hashed with bcrypt *when they log in*. If a user never logs in, their password will remain hashed with MD5 in your database forever.

**Why doesn't this plugin re-hash all existing passwords in the database?**

Right now it's beyond the scope of the plugin. We want to keep it simple and straightforward. This is probably best left up to the individual developer or maybe a separate plugin in the future. See https://github.com/roots/wp-password-bcrypt/issues/6 for more details.

**What happens if I remove/deactivate the plugin?**

If you are using PHP 5.4, it will break and you may need to reset password. But if you are usgin PHP 5.5+, magically, everything still works. See this [comment](https://github.com/roots/wp-password-bcrypt/issues/7#issuecomment-190919884) for more details.

Any existing bcrypt hashed passwords will remain that way. Any new users or users resetting a password will get a new MD5 hashed password.

**What's wrong with using this as a plugin instead of a must-use plugin?**

As explained above, you don't want to disable this plugin once you've enabled it. Installing this in `plugins` (as a "normal" plugin) instead of in `mu-plugins` (as a must-use plugin) makes it possible for an admin user to accidentally disable it.

**How is this different than other plugins which already exist?**

There are a [few plugins](https://en-gb.wordpress.org/plugins/search.php?q=bcrypt) that exist which enable bcrypt. This plugin is different because we bypass the `PasswordHash` class and the `phpass` library that WordPress core uses. This plugin uses PHP's built-in `password_hash` and `password_verify` functions directly to only support PHP 5.5+.

## Further Reading

* `password_hash` [RFC](https://wiki.php.net/rfc/password_hash)
* [Secure Password Storage in PHP](https://paragonie.com/blog/2016/02/how-safely-store-password-in-2016#php)
* [How To Safely Store A Password](https://codahale.com/how-to-safely-store-a-password/)

## Contributors

This plugin is based on a [Gist](https://gist.github.com/Einkoro/11078301) by [@Einkoro](https://github.com/Einkoro) and a [plugin](https://github.com/roots/wp-password-bcrypt) by the Roots team.
