<?php

cLoader::library('sql/cmfMySql');
//cLoader::library('user/cUser');
cLoader::library('patterns/cPatternsStaticRegistry');

class cRegister extends cPatternsStaticRegistry {

    // получить экземпляр cmfPDO
    public static function sql() {
        return self::register('cmfMySql');
    }

    // получить экземпляр Пользователя
//    public static function getUser() {
//        return self::register('cUser');
//    }

    public static function adminId() {
        return self::admin()->getId();
    }

}

?>