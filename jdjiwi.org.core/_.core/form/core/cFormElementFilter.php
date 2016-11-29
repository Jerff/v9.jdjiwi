<?php

class cFormElementFilter extends cFormCore {
    /* === убрать === */

    private $mFilter = array();

    public function &add() {
        $arg = func_get_args();
        $func = array_shift($arg);
        if (!isset($this->mFilter[$func])) {
            $this->mFilter[$func] = $arg;
        }
        return $this;
    }

    // настройка
    public function &__call($name, $arguments) {
        if (empty($arguments)) {
            $this->add($name, true);
        } else if (count($arguments) == 1) {
            $this->add($name, $arguments[0]);
        } else {
            $this->add($name, $arguments);
        }
        return $this;
    }

    private function &all() {
        return $this->mFilter;
    }

    /* === /убрать === */

    private function processing($value, $func, $arg) {
        if ($func = ($this->config()->filter()->$func)) {
            if (is_array($arg)) {
                array_unshift($arg, $this->config()->error()->filter());
                array_unshift($arg, $value);
            } else {
                $arg = array($value, $this->config()->error()->filter(), $arg);
            }
//            pre('$func '. $func);
            if ($error = call_user_func_array($func, $arg)) {
                $this->error()->set($this->parent()->name(), $error);
                return false;
            }
        }
        return true;
    }

    // фильтрация данных
    public function start($value) {
        $parent = $this->parent();
        foreach ($this->parent()->settings()->all() as $k => $v) {
            $this->processing($value, $k, $v);
        }
        /* === убрать === */

        foreach ($this->all() as $func => $arg) {
            $this->processing($value, $func, $arg);
        }
        /* === /убрать === */
        return $value;
    }

}

?>