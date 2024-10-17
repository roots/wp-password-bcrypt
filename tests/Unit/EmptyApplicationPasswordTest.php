<?php

namespace Roots\PasswordBcrypt\Tests\Unit;

use Roots\PasswordBcrypt\Tests\TestCase;
use Roots\PasswordBcrypt\Tests\Constants;

use function Brain\Monkey\Functions\expect;
use function Brain\Monkey\Filters\expectApplied;

class EmptyApplicationPasswordTest extends TestCase
{

    /**
     * @test
     * @runInSeparateProcess
     */
    public function phpass_application_passwords_should_be_verified_and_converted_to_bcrypt()
    {
        require_once __DIR__ . '/../EmptyWPApplicationPasswords.php';

        expect('get_userdata')
            ->once()
            ->with(Constants::USER_ID)
            ->andReturn([]);

        expectApplied('application_password_is_api_request')
            ->andReturn(true);

        $this
            ->wpHasher()
            ->shouldReceive('CheckPassword')
            ->times(3)
            ->andReturnValues([true, true, false]);

        $hash = wp_set_password(Constants::PASSWORD, Constants::USER_ID);

        $this->assertIsString($hash);
    }
}
