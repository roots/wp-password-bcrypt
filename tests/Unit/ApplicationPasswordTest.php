<?php

namespace Roots\PasswordBcrypt\Tests\Unit;

use Roots\PasswordBcrypt\Tests\TestCase;
use Roots\PasswordBcrypt\Tests\Constants;
use Mockery;

use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Filters\expectApplied;

class ApplicationPasswordTest extends TestCase
{

    /**
     * @test
     * @runInSeparateProcess
     */
    public function phpass_application_passwords_should_be_verified_and_converted_to_bcrypt()
    {
        require_once __DIR__ . '/../WPApplicationPasswords.php';

        expectApplied('application_password_is_api_request')
            ->andReturn(true);

        $this
            ->wpHasher()
            ->shouldReceive('CheckPassword')
            ->times(3)
            ->andReturnValues([true, true, false]);

        expect('get_userdata')
            ->once()
            ->with(Constants::USER_ID)
            ->andReturn([]);

        expect('update_user_meta')
            ->once()
            ->withArgs(function (...$args) {
                [$userId, $metaKey, $passwords] = $args;

                if ($userId != Constants::USER_ID) {
                    return false;
                }

                if ($metaKey != \WP_Application_Passwords::USERMETA_KEY_APPLICATION_PASSWORDS) {
                    return false;
                }

                if (count($passwords) != 3) {
                    return false;
                }

                if (!key_exists(0, $passwords)) {
                    return false;
                }

                $passwords = array_map((function ($item) {
                    return $item['password'];
                }), $passwords);

                [$pw1, $pw2, $pw3] = $passwords;

                if (!password_verify(Constants::PASSWORD, $pw1)) {
                    return false;
                }

                if (!password_verify(Constants::PASSWORD, $pw2)) {
                    return false;
                }

                if (password_verify(Constants::PASSWORD, $pw3)) {
                    return false;
                }

                return true;
            });

        $hash = wp_set_password(Constants::PASSWORD, Constants::USER_ID);

        $this->assertIsString($hash);
    }
}
