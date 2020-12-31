<?php

namespace Roots\PasswordBcrypt\Tests;

use Brain\Monkey;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class TestCaseLegacy extends MockeryTestCase
{
    use MocksWpdb;
    use MocksWpHasher;

    protected function setUp()
    {
        Monkey\setUp();
        parent::setUp();
    }

    protected function tearDown()
    {
        Monkey\tearDown();
        parent::tearDown();
    }
}