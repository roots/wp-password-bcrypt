<?php // phpcs:disable PSR1.Files.SideEffects.FoundWithSymbols

namespace Roots\PasswordBcrypt\Tests;

define('ABSPATH', __DIR__ . '/__fixtures__/wp/');
define('WPINC', 'wp-includes');

if (version_compare(phpversion(), '7.1', '<')) {
    class_alias(TestCaseLegacy::class, 'Roots\PasswordBcrypt\Tests\TestCase');
}

require __DIR__ . '/../vendor/autoload.php';
