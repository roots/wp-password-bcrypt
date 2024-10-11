<?php

namespace Roots\PasswordBcrypt\Tests\Unit;

use Roots\PasswordBcrypt\Tests\TestCase;
use Roots\PasswordBcrypt\Tests\Constants;

use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Filters\expectApplied;
use function Brain\Monkey\Actions\expectDone;

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

        expect('get_userdata')
            ->once()
            ->andReturn([]);

        expect('clean_user_cache')
            ->once()
            ->andReturn(true);

        $hash = wp_set_password(Constants::PASSWORD, Constants::USER_ID);

        $this->assertIsString($hash);
        $this->assertTrue(
            password_verify(Constants::PASSWORD, $hash)
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
    public function setting_password_does_action()
    {
        $data = [
            'ID' => Constants::USER_ID,
        ];

        expect('get_userdata')
            ->once()
            ->andReturn($data);

        expect('clean_user_cache')
            ->once()
            ->andReturn(true);

        expectDone('wp_set_password')
            ->once()
            ->with(Constants::PASSWORD, Constants::USER_ID, $data);

        wp_set_password(Constants::PASSWORD, Constants::USER_ID);
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

        expect('get_userdata')
            ->once()
            ->andReturn([]);

        expect('clean_user_cache')
            ->once()
            ->andReturn(true);

        $this->assertTrue(
            wp_check_password(Constants::PASSWORD, Constants::PHPPASS_HASH, Constants::USER_ID)
        );
    }

    /** @test */
    public function wp_hasher_global_should_be_automatically_assigned()
    {
        global $wp_hasher;

        $this
            ->wpdb()
            ->shouldReceive('update')
            ->withAnyArgs()
            ->andReturnNull();

        $this
            ->wpHasher() // 👈🏼 global is assigned here
            ->shouldReceive('CheckPassword')
            ->once()
            ->with(Constants::PASSWORD, Constants::PHPPASS_HASH)
            ->andReturn(true);

        expect('get_userdata')
            ->once()
            ->andReturn([]);

        expect('clean_user_cache')
            ->once()
            ->andReturn(true);


        $class_name = get_class($wp_hasher);
        $wp_hasher = null;


        $this->assertNotInstanceOf($class_name, $wp_hasher);

        $this->assertTrue(
            wp_check_password(Constants::PASSWORD, Constants::PHPPASS_HASH, Constants::USER_ID)
        );

        $this->assertInstanceOf($class_name, $wp_hasher);
    }
}
