<?php

namespace Roots\PasswordBcrypt\Tests\Unit;

use Roots\PasswordBcrypt\Tests\TestCase;

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
            password_verify(self::$password, wp_set_password(self::$password, self::$userId))
        );
    }

    /** @test */
    public function hashing_password_applies_filter()
    {
        wp_hash_password(self::$password);

        expectApplied('wp_hash_password_options')
            ->andReturn(self::$bcryptHash);
    }

    /** @test */
    public function bcrypt_passwords_should_be_verified()
    {
        $this
            ->wpHasher()
            ->shouldReceive('CheckPassword')
            ->once()
            ->with(self::$password, self::$invalidHash)
            ->andReturn(false);

        $this->assertTrue(
            wp_check_password(self::$password, self::$bcryptHash)
        );

        $this->assertFalse(
            wp_check_password(self::$password, self::$invalidHash)
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
            ->with(self::$password, self::$phpassHash)
            ->andReturn(true);

        expect('clean_user_cache')
            ->once()
            ->andReturn(true);

        $this->assertTrue(
            wp_check_password(self::$password, self::$phpassHash, self::$userId)
        );
    }
}
