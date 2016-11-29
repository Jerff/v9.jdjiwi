<?php

class cmfFormConfig {

    private $config = array(
        'textareaMax'=>65535
    );

    public function is($k) {
        return isset($this->config[$k]);
    }

    public function get($k) {
        return get($this->config, $k, $k);
    }

    public function set($k, $v) {
        $this->config[$k] = $v;
    }

}

?>