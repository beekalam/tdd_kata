<?php

namespace Tests\Unit;

use App\orm\Migration;
use PHPUnit\Framework\TestCase;

class MigrationTest extends TestCase
{

    /** @test */
    function it_()
    {
        $migration = new Migration();
        $migration->build("create_users_table", function ($table) {
            $table->build('users', function ($table) {
                $table->increments('id');
                $table->string('name');
            });
        })->build('create_customers_table', function ($table) {
            $table->build('customers', function ($table) {
                $table->increments('id');
                $table->string('name');
            });
        });

        $sql = "create table 'users'(id int(11) not null auto_increment,name varchar(255) not null) engine=innodb" . "\n" .
        "create table 'customers'(id int(11) not null auto_increment,name varchar(255) not null) engine=innodb";

        $this->assertEquals($sql, $migration->toString());

    }
}