<?php


namespace App\orm;


class TableBuilder
{

    private $tableName;
    private $cols = [];

    public function build($tableName, $callable)
    {
        $this->tableName = $tableName;
        $callable($this);
    }

    public function __call($name, $arguments)
    {
        if ($name == "integer" || $name == "increments" || $name == "text" || $name == "float" || $name == "double") {
            $this->cols[$arguments[0]] = new ColumnBuilder();
            return $this->cols[$arguments[0]]->$name($arguments[0]);
        } else if ($name == "string") {
            $this->cols[$arguments[0]] = new ColumnBuilder();
            return $this->cols[$arguments[0]]->$name($arguments[0], isset($arguments[1]) ? $arguments[1] : 255);
        }
    }

    public function toString()
    {
        $cols = [];
        foreach ($this->cols as $col) {
            $cols[] = $col->toString();
        }
        $cols = implode(",", $cols);
        return "create table '{$this->tableName}'({$cols}) engine=innodb";
    }


}