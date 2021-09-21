<?php

namespace Roots\PasswordBcrypt\Tests\Unit;

use Roots\PasswordBcrypt\Tests\TestCase;
use Roots\PasswordBcrypt\Tests\Constants;

use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Filters\expectApplied;

class UserPasswordTest extends TestCase
{
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
            password_verify(Constants::PASSWORD, wp_set_password(Constants::PASSWORD, Constants::USER_ID))
        );
    }

    /** @test */
    public function hashing_password_applies_filter()
    {
        wp_hash_password(Constants::PASSWORD);

        expectApplied('wp_hash_password_options')
            ->andReturn(Constants::BCRYPT_HASH);
    }

    /** @test */
    public function bcrypt_passwords_should_be_verified()
    {
        $this
            ->wpHasher()
            ->shouldReceive('CheckPassword')
            ->once()
            ->with(Constants::PASSWORD, Constants::INVALID_HASH)
            ->andReturn(false);

        $this->assertTrue(
            wp_check_password(Constants::PASSWORD, Constants::BCRYPT_HASH)
        );

        $this->assertFalse(
            wp_check_password(Constants::PASSWORD, Constants::INVALID_HASH)
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
            ->with(Constants::PASSWORD, Constants::PHPPASS_HASH)
            ->andReturn(true);

        expect('clean_user_cache')
            ->once()
            ->andReturn(true);

        $this->assertTrue(
            wp_check_password(Constants::PASSWORD, Constants::PHPPASS_HASH, Constants::USER_ID)
        );
    }
}
