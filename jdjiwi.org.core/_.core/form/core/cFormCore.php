<?php

cLoader::library('form/core/cFormRegister');
cLoader::library('form/core/cFormConfig');
cLoader::library('form/core/cFormSettings');

class cFormCore extends cFormRegister {

    // конфигурация
    public function config() {
        return $this->register('cFormConfig', self::collective);
    }

    // ошибки
    public function error() {
        return $this->register('cFormError', self::collective);
    }

    // проверка валидности форм
    public function security() {
        return $this->register('cFormSecurity', self::collective);
    }

}

?>