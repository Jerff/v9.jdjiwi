<?php

class cmfFormError {

    const requiredFields = 'Заполните обязательные поля!';

    static private $error = null;

    static public function set($error) {
        self::$error = cmfFormConfig::get($error);
    }

    static public function is() {
        return (bool) self::$error;
    }

    static public function get() {
        $error = self::$error;
        self::$error = null;
        return $error;
    }

}

?>