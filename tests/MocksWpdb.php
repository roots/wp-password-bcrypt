<?php

namespace Roots\PasswordBcrypt\Tests;

use Mockery;

trait MocksWpdb
{
    protected function wpdb($properties = ['users' => 'wp_users'])
    {
        global $wpdb;

        $wpdb = Mockery::mock('overload:wpdb');

        foreach ($properties as $property => $value) {
            $wpdb->{$property} = $value;
        }

        return $wpdb;
    }
}
