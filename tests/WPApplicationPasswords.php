<?php

use Roots\PasswordBcrypt\Tests\Constants;

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace
// phpcs:disable Squiz.Classes.ValidClassName.NotCamelCaps
class WP_Application_Passwords
{
    public const USERMETA_KEY_APPLICATION_PASSWORDS = '_application_passwords';

    public static function get_user_application_passwords($userId)
    {
        return [
            [
                'password' => Constants::PHPPASS_HASH
            ],
            [
                'password' => Constants::BCRYPT_HASH
            ],
            [
                'password' => Constants::INVALID_HASH
            ]
        ];
    }
}
