<?php

namespace Roots\PasswordBcrypt\Tests;

use Brain\Monkey;
use PHPUnit_Framework_TestCase;

class WpPasswordBcryptTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        Monkey::setUpWP();
    }

    protected function tearDown()
    {
        Monkey::tearDownWP();
    }

    public function testCheckPasswordShouldVerifyPassword()
    {
        $check = wp_check_password('password', 'randomhash');
        $this->assertFalse($check);
    }

    public function testHashPasswordAppliesFilter()
    {
        wp_hash_password('password');

        $this->assertTrue(Monkey::filters()->applied('wp_hash_password_options') === 1);
    }
}
