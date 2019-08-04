<?php
namespace App\FluentFactory;

use http\Exception\InvalidArgumentException;

class F{
	private $kalss;	
	private $lastResult = null;

	public function __construct($kalss){
		$this->kalss = $kalss;
	}

	public static function f($kalss){
		return new F($kalss);
	}

	public function __call($name, $arguments){
		if($name == "getLastResult"){
		    return $this->lastResult;
        }else{
            if(!method_exists($this->kalss, $name))
                throw new \InvalidArgumentException('');
            $this->lastResult = call_user_func_array(array($this->kalss, $name), $arguments);
        }
		return $this;
	}

    public function getLastResult()
    {
        return $this->lastResult;
	}
		
}