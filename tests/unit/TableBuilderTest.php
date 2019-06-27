<?php

namespace Tests\Unit;

use App\orm\TableBuilder;
use PHPUnit\Framework\TestCase;

class TableBuilderTest extends TestCase
{
    private $tableBuilder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tableBuilder = new TableBuilder();
    }


    /** @test */
    function can_create_table()
    {
        $this->tableBuilder->build("table_name",function($table){
            $table->integer('col_name');
        });
        $this->assertEquals("create table 'table_name'(col_name int(11) not null) engine=innodb",$this->tableBuilder->toString());
    }

    /** @test */
    function can_create_table_with_multiple_columns()
    {
        $this->tableBuilder->build("table_name",function($table){
            $table->increments('id');
            $table->integer('count');
            $table->text('desc')->nullable();
        });
        $this->assertEquals(
            "create table 'table_name'(id int(11) not null auto_increment,count int(11) not null,desc text null) engine=innodb",
            $this->tableBuilder->toString());
    }

    /** @test */
    function can_create_float_column()
    {
        $this->tableBuilder->build("table_name",function($table){
            $table->float('price')->nullable();
        });

        $this->assertEquals(
            "create table 'table_name'(price float null) engine=innodb",
            $this->tableBuilder->toString());
    }

    /** @test */
    function can_cretae_double_column()
    {
        $this->tableBuilder->build("table_name",function($table){
            $table->double('price')->nullable();
        });

        $this->assertEquals(
            "create table 'table_name'(price double null) engine=innodb",
            $this->tableBuilder->toString());
    }

    /** @test */
    function can_create_string_column()
    {
        $this->tableBuilder->build("table_name",function($table){
            $table->string('desc',20);
        });

        $this->assertEquals(
            "create table 'table_name'(desc varchar(20) not null) engine=innodb",
            $this->tableBuilder->toString());
    }

    /** @test */
    function can_create_string_column_with_default_length_of_255()
    {
        $this->tableBuilder->build("table_name",function($table){
            $table->string('desc');
        });

        $this->assertEquals(
            "create table 'table_name'(desc varchar(255) not null) engine=innodb",
            $this->tableBuilder->toString());

    }



}