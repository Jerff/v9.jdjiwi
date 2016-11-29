<?php

class cInputHeader {

    private $header = null;

    protected function init() {
        if (!empty($this->headers))
            return;

        if (function_exists('apache_request_headers')) {
            $this->headers = apache_request_headers();
        } else {
            $this->headers = array(
                'Content-Type' => isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : @getenv('CONTENT_TYPE')
            );
            foreach ($_SERVER as $key => $val) {
                if (strncmp($key, 'HTTP_', 5) === 0) {
                    $k = str_replace(array(' ', '_'), array('-', ' '), ucwords(strtolower(substr($key, 5))));
                    $this->headers[$k] = $_SERVER[$key];
                }
            }
        }
    }

    public function is($n) {
        $this->init();
        return isset($this->header[$n]);
    }

    public function get($n, $d = null) {
        $this->init();
        return get($this->header, $n, $d);
    }

    public function all() {
        $this->init();
        return $this->header;
    }

}

?>
