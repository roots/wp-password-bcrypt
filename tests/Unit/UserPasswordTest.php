<?php

namespace Roots\PasswordBcrypt\Tests\Unit;

use Roots\PasswordBcrypt\Tests\TestCase;

use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Filters\expectApplied;

class UserPasswordTest extends TestCase
{
    const PASSWORD = 'password';
    const HASH_BCRYPT = '$2y$10$KIMXDMJq9camkaNHkdrmcOaYJ0AT9lvovEf92yWA34sKdfnn97F9i';
    const HASH_PHPASS = '$P$BDMJH/qCLaUc5Lj8Oiwp7XmWzrCcJ21';
    const HASH_BAD = 'NOTAREALHASH';

    /** @test */
    public function a_password_is_hashed_using_bcrypt()
    {
        $this
            ->wpdb()
            ->shouldReceive('update')
            ->withAnyArgs()
            ->andReturnNull();

        expect('clean_user_cache')
            ->once()
            ->andReturn(true);

        $this->assertTrue(
            password_verify(self::PASSWORD, wp_set_password(self::PASSWORD, 1))
        );
    }

    /** @test */
    public function hashing_password_applies_filter()
    {
        wp_hash_password(self::PASSWORD);

        expectApplied('wp_hash_password_options')
            ->andReturn(self::HASH_BCRYPT);
    }

    /** @test */
    public function bcrypt_passwords_should_be_verified()
    {
        $this
            ->wpHasher()
            ->shouldReceive('CheckPassword')
            ->once()
            ->with(self::PASSWORD, self::HASH_BAD)
            ->andReturn(false);

        $this->assertTrue(
            wp_check_password(self::PASSWORD, self::HASH_BCRYPT)
        );

        $this->assertFalse(
            wp_check_password(self::PASSWORD, self::HASH_BAD)
        );
    }

    /** @test */
    public function phpass_passwords_should_be_verified_and_converted_to_bcrypt()
    {
        $this
            ->wpdb()
            ->shouldReceive('update')
            ->withAnyArgs()
            ->andReturnNull();

        $this
            ->wpHasher()
            ->shouldReceive('CheckPassword')
            ->once()
            ->with(self::PASSWORD, self::HASH_PHPASS)
            ->andReturn(true);

        expect('clean_user_cache')
            ->once()
            ->andReturn(true);

        $this->assertTrue(
            wp_check_password(self::PASSWORD, self::HASH_PHPASS, 1)
        );
    }
}
