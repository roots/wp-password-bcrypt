<?php

namespace Roots\PasswordBcrypt\Tests;

use Brain\Monkey;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class TestCaseLegacy extends MockeryTestCase
{
    use MocksWpdb;
    use MocksWpHasher;

    /**
     * The user ID to use while testing the plugin.
     *
     * @var int
     */
    protected static $userId = 1;

    /**
     * The password to use while testing the plugin.
     *
     * @var string
     */
    protected static $password = 'password';

    /**
     * The expected password bcrypt hash.
     *
     * @var string
     */
    protected static $bcryptHash = '$2y$10$KIMXDMJq9camkaNHkdrmcOaYJ0AT9lvovEf92yWA34sKdfnn97F9i';

    /**
     * The expected password PHPass hash.
     *
     * @var string
     */
    protected static $phpassHash = '$P$BDMJH/qCLaUc5Lj8Oiwp7XmWzrCcJ21';

    /**
     * The expected invalid hash.
     *
     * @var string
     */
    protected static $invalidHash = 'NOT_A_REAL_HASH';

    /**
     * Setup the test case.
     *
     * @return void
     */
    protected function setUp()
    {
        Monkey\setUp();
        parent::setUp();
    }

    /**
     * Tear down the test case.
     *
     * @return void
     */
    protected function tearDown()
    {
        Monkey\tearDown();
        parent::tearDown();
    }
}
