<?php

class cPatternsSettings {

    private $mSettings = array();
    private $mReplace = array();
    private $mDelete = array();

    public function replace($s, $r) {
        if(isset($this->mReplace[$r])) {
            $this->replace($s, $this->mReplace[$r]);
        } else {
            $this->mReplace[$s] = $r;
        }
    }

    public function delete($s, $r) {
        $this->mDelete[$s] = $r;
        unset($this->mSettings[$s], $this->mReplace[$s]);
    }

    public function get($n) {
        return get($this->mSettings, $n);
    }

    public function get2($n, $n2) {
        return get2($this->mSettings, $n, $n2);
    }

    public function get3($n, $n2, $n3) {
        return get3($this->mSettings, $n, $n2, $n3);
    }

    public function __get($name) {
        return get($this->mSettings, $name);
    }

    public function is($name) {
        return isset($this->mSettings[$name]);
    }

    // установить
    protected function set($k, $v) {
        $this->mSettings[get($this->mReplace, $k, $k)] = $v;
    }

    // вернуть список
    public function &all() {
        return $this->mSettings;
    }

    // настройка
    public function &__call($name, $arguments) {
        if(isset($this->mDelete[$name])) {
            return $this;
        }
        if (empty($arguments)) {
            $this->set($name, true);
        } else if (count($arguments) == 1) {
            $this->set($name, $arguments[0]);
        } else {
            $this->set($name, $arguments);
        }
        return $this;
    }

}

?>