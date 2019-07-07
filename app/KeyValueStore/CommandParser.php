<?php


namespace App\KeyValueStore;


class CommandParser
{

    /**
     * CommandParser constructor.
     */
    public function __construct($str)
    {
        $this->str = trim($str);
        $this->commands = ['get', 'set', 'has', 'delete'];
        $this->validCommand = true;
        $this->parts = [];
        $this->parse();
    }

    private function parse()
    {
        if (empty($this->str)) $this->validCommand = false;
        $this->parts = explode(' ', $this->str);
        if (count($this->parts) < 2) $this->validCommand = false;
        if (!in_array($this->parts[0], $this->commands)) $this->validCommand = false;
    }

    public function hasValidCommand()
    {
        return $this->validCommand;
    }

    public function getCommand()
    {
        return strtolower($this->parts[0]);
    }

    public function getParams()
    {
        return array_slice($this->parts,1);
    }
}