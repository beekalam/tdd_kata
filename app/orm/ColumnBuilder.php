<?php


namespace App\orm;


class ColumnBuilder
{

    private $colName;
    private $colType;
    private $null = "not null";
    private $defaultvalue = null;
    private $autoIncrement = "";
    private $hasForeignKey = false;
    private $reference_col;
    private $reference_table;

    public function integer($colName)
    {
        $this->colName = $colName;
        $this->colType = "int(11)";
        return $this;
    }

    public function tinyint($colName)
    {
        $this->colName = $colName;
        $this->colType = "tinyint(4)";
        return $this;
    }

    public function string($colName, $size = 255)
    {
        $this->colName = $colName;
        $this->colType = "varchar({$size})";
        return $this;
    }

    public function nullable()
    {
        $this->null = "null";
        return $this;
    }

    public function bigint($colName)
    {
        $this->colName = $colName;
        $this->colType = "bigint(20)";
        return $this;
    }

    public function text($colName)
    {
        $this->colName = $colName;
        $this->colType = "text";
        return $this;
    }

    public function float($colName)
    {
        $this->colName = $colName;
        $this->colType = "float";
        return $this;
    }

    public function double($colName)
    {
        $this->colName = $colName;
        $this->colType = "double";
        return $this;
    }

    public function defaultValue($value)
    {
        $this->defaultvalue = $value;
        return $this;
    }

    public function increments($colName)
    {
        $this->integer($colName);
        $this->autoIncrement = "auto_increment";
        return $this;
    }

    public function foreign()
    {
        $this->hasForeignKey = true;
        return $this;
    }

    public function references($col)
    {
        $this->reference_col = $col;
        return $this;
    }

    public function on($table)
    {
        $this->reference_table = $table;
        return $this;
    }

    public function toString()
    {
        $str = "{$this->colName} {$this->colType} {$this->null}";
        if (empty($this->autoIncrement))
            $str .= is_null($this->defaultvalue) ? "" : " default '{$this->defaultvalue}'";
        else
            $str .= " {$this->autoIncrement}";
        return $str;
    }


    public function hasForeignkey()
    {
        return $this->hasForeignKey;
    }

    public function foreignRelationString($table)
    {
        $str = '';
        if ($this->hasForeignKey) {
            $str .= "constraint `{$table}_{$this->colName}_foreign` foreign key ('{$this->colName}') references `{$this->reference_table}` ('{$this->reference_col}')";
        }
        return $str;
    }
}