<?php

namespace Roots\PasswordBcrypt\Tests;

class Constants
{
    /**
     * The user ID to use while testing the plugin.
     *
     * @const int
     */
    public const USER_ID = 1;

    /**
     * The password to use while testing the plugin.
     *
     * @const string
     */
    public const PASSWORD = 'password';

    /**
     * The expected password bcrypt hash.
     *
     * @const string
     */
    public const BCRYPT_HASH = '$2y$10$KIMXDMJq9camkaNHkdrmcOaYJ0AT9lvovEf92yWA34sKdfnn97F9i';

    /**
     * The expected password PHPass hash.
     *
     * @const string
     */
    public const PHPPASS_HASH = '$P$BDMJH/qCLaUc5Lj8Oiwp7XmWzrCcJ21';

    /**
     * The expected invalid hash.
     *
     * @const string
     */
    public const INVALID_HASH = 'NOT_A_REAL_HASH';
}
