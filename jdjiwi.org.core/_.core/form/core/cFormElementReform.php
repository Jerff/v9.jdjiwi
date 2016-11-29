<?php

class cFormElementReform extends cFormCore {
    /* === убрать === */

    private $mReform = array();

    // добавить функцию
    public function &add($reform, $arg = null) {
        $this->mReform[$this->config()
                        ->reform()->$reform] = $arg;
        return $this;
    }

    // список
    private function &get() {
        return $this->mReform;
    }

    /* === /убрать === */

    // преобразование данных
    private function init($value, $isView) {
        $parent = $this->parent();
        foreach ($this->parent()->settings()->all() as $k => $v) {
            if ($func = ($this->config()->reform()->$k)) {
//                pre('$func '. $func, $value, $v, $isView);
                $value = call_user_func($func, $value, $v, $isView);
            }
        }
        /* === убрать === */
        foreach ($this->get() as $func => $arg) {
            $value = call_user_func($func, $value, $arg, $isView);
        }
        return $value;
        /* === /убрать === */
    }

    // фильтрованные данные
    public function data($value) {
        return $this->init($value, false);
    }

    // данные для показа пользователю
    public function view($value) {
        return $this->init($value, true);
    }

}

?>