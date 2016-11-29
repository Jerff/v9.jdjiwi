<?php

class admin_command_recursion_core {

    private $call = false;
    private $recursion = false;
    private $data = false;

    static public function init(&$res, $handler, $recursion = 1) {
        return new self(array($res, $handler), $recursion);
    }

    public function __construct($call, $recursion = 1, $data = array()) {
        $this->call = $call;
        $this->recursion = $recursion - 1;
        $this->data = $data;
    }

    public function __call($name, $arguments) {
        $this->data[] = $name;
        if ($this->recursion) {
            return new self($this->call, $this->recursion, $this->data);
        } else {
            call_user_func_array($this->call, $this->data);
        }
    }

}

?>