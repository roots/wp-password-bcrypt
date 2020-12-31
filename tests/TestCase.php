<?php

namespace Roots\PasswordBcrypt\Tests;

use Mockery as m;
use Brain\Monkey;
use Mockery\Adapter\Phpunit\MockeryTestCase;

// phpcs:disable PHPCompatibility.FunctionDeclarations.NewReturnTypeDeclarations.voidFound

class TestCase extends MockeryTestCase
{
    const PASSWORD = 'password';
    const HASH_BCRYPT = '$2y$10$KIMXDMJq9camkaNHkdrmcOaYJ0AT9lvovEf92yWA34sKdfnn97F9i';
    const HASH_PHPASS = '$P$BDMJH/qCLaUc5Lj8Oiwp7XmWzrCcJ21';

    protected function setUp(): void
    {
        Monkey\setUp();
        parent::setUp();
    }

    protected function tearDown(): void
    {
        Monkey\tearDown();
        parent::tearDown();
    }

    protected function wpHasher()
    {
        global $wp_hasher;

        return $wp_hasher = m::mock('overload:PasswordHash');
    }

    protected function wpdb($properties = ['users' => 'wp_users'])
    {
        global $wpdb;

        $wpdb = m::mock('overload:wpdb');

        foreach ($properties as $property => $value) {
            $wpdb->{$property} = $value;
        }

        return $wpdb;
    }
}
