<?php

class sql_settings_core extends admin_core {

    private $mConfig = array();

    protected function config($name, $value) {
        if (!empty($value)) {
            $this->mConfig[$name] = $value;
        }
        return $this->mConfig[$name];
    }

    protected function callable($name, $func, $args) {
        list($value) = $args;
        if (is_callable($value)) {
            $this->config($name, $value);
        } else {
            $settings = $this->get($name);
            return call_user_func_array(empty($settings) ? $func : $settings, $args);
        }
    }

    protected function get($name) {
        return $this->mConfig[$name];
    }

    // тaблицы
    public function table($value = null) {
        return $this->config('table', $value);
    }

    // сортировка
    protected function sort($value = null) {
        return $this->config('sort', $value);
    }

    // данные
    protected function fields($value = null) {
        return $this->config('fields', $value);
    }

    // where
    protected function where($value = null) {
        return $this->callable('where', function() {
                            return $this->whereId($this->id()->get());
                        }, func_get_args());
        /*
          protected function getWhere() {
          return $this->getWhereId($this->id()->get());
          }
         */
    }

    // whereId
    protected function whereId($value = null) {
        return $this->callable('whereId', function($id) {
                            return array('id' => $id);
                        }, func_get_args());
        /*
          protected function getWhereId($id) {
          return array('id' => $id);
          }
         */
    }

    // whereFilter
    protected function whereFilter($value) {
        return $this->callable('whereId', function() {
                            return array(1);
                        }, func_get_args());

        $value = $this->config('whereFilter', $value);
        return empty($value) ? array(1) : $value;
        /*
          protected function getWhereFilter() {
          return array(1);
          }
         */
    }

}

?>