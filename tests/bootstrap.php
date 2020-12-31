<?php

namespace Roots\PasswordBcrypt\Tests;

if (version_compare(phpversion(), '7.1', '<')) {
    class_alias(TestCaseLegacy::class, 'Roots\PasswordBcrypt\Tests\TestCase');
}

define('ABSPATH', __DIR__ . '/__fixtures__/wp/');
define('WPINC', 'wp-includes');

require __DIR__ . '/../vendor/autoload.php';