<?php

namespace Roots\PasswordBcrypt\Tests;

use Brain\Monkey;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class TestCaseLegacy extends MockeryTestCase
{
    use MocksWpdb;
    use MocksWpHasher;

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
