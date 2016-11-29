<?php

class admin_record_id_core {

    private $name = null;
    private $value = null;

    public function isSingleton() {

    }

    public function isChage() {
        return (bool) $this->name;
    }

    /* === имя переменной в которой хранится значения === */

    //
    public function setName($name) {
        $this->name = $name;
    }

    protected function name() {
        return $this->name ? : 'id';
    }

    /* === /имя переменной в которой хранится значения === */

    /* === установка & получение id === */

    public function isEmpty() {
        return (bool) $this->get();
    }

    public function get() {
        return cInput::get()->get($this->name());
    }

    public function set($id, $isFilter = false) {
        if ($isFilter) {
            $id = str_replace(array('"', "'", '<', '>'), '', $id);
        }
        cInput::get()->set($this->name(), $id);
    }

    /* === /установка & получение id === */

    // временная инициализация другим значением
    // сохранение истинное значения во временной переменной
    public function initBackup($id) {
        $this->value = $this->get();
        $this->set($id);
    }

    public function reset() {
        $this->set($this->value);
    }

}

?>