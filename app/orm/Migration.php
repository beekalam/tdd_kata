<?php


namespace App\orm;


class Migration
{
    private $migrationName;
    private $tables;

    public function build($migrationName, $callable)
    {
        $this->migrationName = $migrationName;
        $tb = new TableBuilder();
        $this->tables[] = &$tb;
        $callable($tb);
        return $this;
    }

    public function toString()
    {
        $tables = [];
        foreach($this->tables as $table){
            $tables[] = $table->toString();
        }
        return implode("\n",$tables);
    }


}