<?php

abstract class cPatternsConfig {

    private $config = array();

    public function __construct($config = null) {
        if (empty($config)) {
            $this->set($this->init());
        } else {
            $this->set($config);
        }
    }

    protected function set($config) {
        $this->config = $config;
    }

    abstract protected function init();

    public function is($name) {
        return isset($this->config[$name]);
    }

    public function replace($search, $replace) {
        return str_replace($search, $replace, $this->config);
    }

    public function __get($name) {
        return get($this->config, $name);
    }

    public function __call($name, $arguments) {
        if (isset($this->config[$name])) {
            $class = get_class($this);
            return new $class($this->config[$name]);
        } else {
            return $this->__get($name);
        }
    }

}

?>