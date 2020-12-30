<?php

namespace Roots\PasswordBcrypt\Tests\Unit;

use Roots\PasswordBcrypt\Tests\TestCase;

use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Functions\expectApplied;

class UserPasswordTest extends TestCase
{
    /** @test */
    public function a_password_is_hashed_using_bcrypt()
    {
        $user_id = 1;

        expect('wp_cache_delete')->once()->andReturn(true);

        $bcrypt_hash = wp_set_password(self::PASSWORD, $user_id);
        $this->assertTrue(password_verify(self::PASSWORD, $bcrypt_hash));
    }

    /** @test */
    public function hashing_password_applies_filter()
    {
        wp_hash_password(self::PASSWORD);

        expectApplied('wp_hash_password_options')->andReturn(self::HASH_BCRYPT);
    }

    /** @test */
    public function bcrypt_passwords_should_be_verified()
    {
        $bad_hash = 'randomhash';

        $bcrypt_check = wp_check_password(self::PASSWORD, self::HASH_BCRYPT);
        $bad_check = wp_check_password(self::PASSWORD, $bad_hash);

        $this->assertTrue($bcrypt_check);
        $this->assertFalse($bad_check);
    }

    /** @test */
    public function phpass_passwords_should_be_verified_and_converted_to_bcrypt()
    {
        global $wp_hasher;

        /** @var int $user_id This is necessary to trigger wp_set_password() */
        $user_id = 1;

        $wp_hasher = Monkey::mock('PasswordHash');
        $wp_hasher
            ->shouldReceive('CheckPassword')
            ->once()
            ->with(self::PASSWORD, self::HASH_PHPASS)
            ->andReturn(true);

        expect('wp_cache_delete')->once()->andReturn(true);

        $phpass_check = wp_check_password(self::PASSWORD, self::HASH_PHPASS, $user_id);
        $this->assertTrue($phpass_check);
    }
}