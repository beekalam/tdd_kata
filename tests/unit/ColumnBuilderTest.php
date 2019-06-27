<?php

namespace Tests\Unit;

use App\orm\ColumnBuilder;
use PHPUnit\Framework\TestCase;

class ColumnBuilderTest extends TestCase
{
    private $columnBuilder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->columnBuilder = new ColumnBuilder();
    }


    /** @test */
    function can_create_int_column()
    {
        $this->assertEquals('col int(11) not null', $this->columnBuilder->integer('col')->toString());
    }

    /** @test */
    function can_create_tinyint_column()
    {
        $this->assertEquals('col tinyint(4) not null', $this->columnBuilder->tinyint('col')->toString());
    }

    /** @test */
    function can_create_string_column()
    {
        $this->assertEquals('col varchar(255) not null', $this->columnBuilder->string('col')->toString());
    }

    /** @test */
    function can_create_string_column_with_size()
    {
        $this->assertEquals('col varchar(600) not null', $this->columnBuilder->string('col', 600)->toString());
    }

    /** @test */
    function can_create_nullable_columns()
    {
        $this->assertEquals('col varchar(600) null', $this->columnBuilder->string('col', 600)->nullable()->toString());
    }

    /** @test */
    function can_create_bigint_column()
    {
        $this->assertEquals('col bigint(20) not null', $this->columnBuilder->bigint('col')->toString());
    }

    /** @test */
    function can_create_text_column()
    {
        $this->assertEquals('col text not null', $this->columnBuilder->text('col')->toString());
    }

    /** @test */
    function can_create_float_column()
    {
        $this->assertEquals('col float not null', $this->columnBuilder->float('col')->toString());
    }

    /** @test */
    function can_create_double_column()
    {
        $this->assertEquals('col double not null', $this->columnBuilder->double('col')->toString());
    }

    /** @test */
    function can_set_default_value_for_column()
    {
        $this->assertEquals("col double not null default '13'", $this->columnBuilder->double('col')->defaultValue(13)->toString());
    }

    /** @test */
    function can_create_auto_increment_column()
    {
        $this->assertEquals("col int(11) not null auto_increment", $this->columnBuilder->increments('col')->toString());
    }
   
}