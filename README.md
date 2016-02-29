# wp-password-bcrypt
[![Build Status](https://travis-ci.org/roots/wp-password-bcrypt.svg)](https://travis-ci.org/roots/wp-password-bcrypt)

wp-password-bcrypt is a WordPress plugin to replace WP's outdated and insecure MD5-based password hashing with the modern and secure [bcrypt](https://en.wikipedia.org/wiki/Bcrypt).

This plugin requires PHP >= 5.5.0 which introduced the built-in `[password_hash](http://php.net/manual/en/function.password-hash.php)` and `[password_verify](http://php.net/manual/en/function.password-verify.php)` functions.

## Requirements

* PHP >= 5.5.0

## Installation

`composer require roots/wp-password-bcrypt`

## The Problem

WordPress still uses an MD5 based password hashing scheme. They are effectively making 25% of websites more insecure because they refuse to bump their minimum PHP requirements. By continuing to allow EOL PHP versions back to 5.2, they can't use newer functions like `password_hash`.

This is a [known](https://core.trac.wordpress.org/ticket/21022) problem which WordPress has ignored for over 4 years now. Not only does WordPress set the insecure default of MD5, they don't do any of the following:

* document this issue
* provide instructions on how to fix it and make it more secure
* notify users on newer PHP versions that they *could* be more secure

What's wrong with MD5? Really simply: it's too cheap and fast to generate hashes.

## The Solution

WordPress did at least one good thing: they made `wp_check_password` and `wp_hash_password` [pluggable](https://codex.wordpress.org/Pluggable_Functions) functions. This means we can define these functions in a plugin and "override" the default ones.

This plugin plugs in 3 functions:

* `wp_check_password`
* `wp_hash_password`
* `wp_set_password`

### `wp_hash_password`

This function is the simplest. This plugin simply calls `password_hash` instead of WP's default password hasher.
The `wp_hash_password_options` filter is available to set the [options](http://php.net/manual/en/function.password-hash.php) that `password_hash` can accept.

### `wp_check_password`

At it's core, this function just calls `password_verify` instead of the default.
However, it also checks if a user's password was *previously* hashed with the old MD5-based hasher and re-hashes it with bcrypt. This means you can still install this plugin on an existing site and everything will work seamlessly.

The `check_password` filter is available just like the default WP function.

### `wp_set_password`

This function is included here verbatim but it with the addition of returning the hash. The default WP function does not return anything which means you end up hashing it twice for no reason.

## Further Reading

* `password_hash` [RFC](https://wiki.php.net/rfc/password_hash)
* [Secure Password Storage in PHP](https://paragonie.com/blog/2016/02/how-safely-store-password-in-2016#php)
* [How To Safely Store A Password](https://codahale.com/how-to-safely-store-a-password/)

## Contributors

This plugin is based off of a [Gist](https://gist.github.com/Einkoro/11078301) by [@Einkoro](https://github.com/Einkoro).

It has been modified and packaged by the Roots team.

## Contributing

Contributions are welcome from everyone. We have [contributing guidelines](CONTRIBUTING.md) to help you get started.

## Community

Keep track of development and community news.

* Participate on the [Roots Discourse](https://discourse.roots.io/)
* Follow [@rootswp on Twitter](https://twitter.com/rootswp)
* Read and subscribe to the [Roots Blog](https://roots.io/blog/)
* Subscribe to the [Roots Newsletter](https://roots.io/subscribe/)
