<?php

class cPagesConfig {

    private $key = array(
        'template' => 't',
        'uri' => 'u',
        'path' => 'p',
        'base' => 'b',
        'noCache' => '!cache',
    );
    private $config = array();

    public function __construct($config) {
        $this->config = $config;
    }

    public function isNoPage() {
        return empty($this->config['u']);
    }

    public function __get($name) {
        return get($this->config, get($this->key, $name, $name));
    }


}

?>
