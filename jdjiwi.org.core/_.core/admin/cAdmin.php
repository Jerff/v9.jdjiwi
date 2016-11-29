<?php

cLoader::library('patterns/cPatternsStaticRegistry');
cLoader::library('admin/cAdminUser');

class cAdmin extends cPatternsStaticRegistry {

    // админ
    static public function user() {
        return self::register('cAdminUser');
    }

    static public function authorization() {
        return self::user()->authorization();
    }

    // шаблоны
    static public function template() {
        return self::register('cAdminTemplate');
    }

    // контроллер
    static public function router() {
        return self::register('cAdminRouter');
    }

    // меню
    static public function menu() {
        return self::register('cAdminMenu');
    }

    // кеш
    static public function cache() {
        return self::register('cAdminCache');
    }

}

?>