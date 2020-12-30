<?php

namespace Roots\PasswordBcrypt\Tests;

use Brain\Monkey;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase as TestCaseFramework;

// phpcs:disable PHPCompatibility.FunctionDeclarations.NewReturnTypeDeclarations.voidFound

class TestCase extends TestCaseFramework
{
    use MockeryPHPUnitIntegration;

    const PASSWORD = 'password';
    const HASH_BCRYPT = '$2y$10$KIMXDMJq9camkaNHkdrmcOaYJ0AT9lvovEf92yWA34sKdfnn97F9i';
    const HASH_PHPASS = '$P$BDMJH/qCLaUc5Lj8Oiwp7XmWzrCcJ21';

    protected function setUp(): void
    {
        Monkey\setUp();
        parent::setUp();

        global $wpdb;

        $wpdb = Monkey::mock('wpdb');
        $wpdb
            ->shouldReceive('update')
            ->withAnyArgs()
            ->andReturnNull();
        $wpdb->users = 'wp_users';
    }

    protected function tearDown(): void
    {
        Monkey\tearDown();
        parent::tearDown();
    }
}
