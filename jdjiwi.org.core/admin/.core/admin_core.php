<?php

// обстрактный интерфейс для модулей админки
abstract class admin_core extends admin_registry_core {
    /* === инициализация === */

//    function __construct() {
//        parent::__construct();
//    }

    protected function init() {

    }

    /* === /инициализация === */



    /* === /общедоступные объекты === */

    // проверка доступа
    public function access() {
        return self::register('admin_access_core', self::collective);
    }

    // фильтры
    public function filter() {
        return self::register('admin_filter_core', self::collective);
    }

        // настройки
    public function settings() {
        return $this->register('admin_settings_core');
    }

    // команды
    public function command() {
        return self::register('admin_command_core', self::collective);
    }

    // id
    public function id() {
        return self::register('admin_record_id_core', self::collective);
    }

    // info
    public function info() {
        return self::register('admin_info_register_core');
    }

    // визуальный редактор
    public function wysiwyng() {
        return self::register('wysiwyng_core', self::collective);
    }

    // хеш
    public function hash($salt = '') {
        return cHashing::hash(get_class() . $this->id()->get() . $salt);
    }

    /* === /общедоступные объекты === */

    // ajax
    public function ajax() {
        return cAjax::get();
    }

    /* === база данных === */

    // драйвер бд
    protected function sql() {
        return cRegister::sql();
    }

}

?>