<?php

//cLoader::library('form/cmfFormConfig');
//cLoader::library('form/cmfFormError');
//
//cLoader::library('form/library/cmfFormLibJs');
//cLoader::library('form/library/cmfFormLibString');
//cLoader::library('form/library/cmfFormLibFilter');
//cLoader::library('form/library/cmfFormLibReform');
//cLoader::library('form/library/cmfFormLibFile');
//cLoader::library('form/library/cmfFormLibImage');
//
//cLoader::library('form/cmfFormElement');
//cLoader::library('form/element/cmfFormText');
//cLoader::library('form/element/cmfFormKcaptcha');
//cLoader::library('form/element/cmfFormPassword');
//cLoader::library('form/element/cmfFormTextarea');
//cLoader::library('form/element/cmfFormCheckbox');
//cLoader::library('form/element/cmfFormSelect');
//cLoader::library('form/element/cmfFormRadio');
//cLoader::library('form/element/cmfFormFile');
//cLoader::library('form/element/cmfFormImage');


cLoader::library('form/core/cFormCore');
cLoader::library('form/core/cFormUpdate');
cLoader::library('form/core/cFormError');
cLoader::library('form/core/cFormHtml');
cLoader::library('form/core/cFormSecurity');
cLoader::library('form/core/cFormProcessing');
cLoader::library('form/cFormElement');

class cForm extends cFormCore implements Iterator {

    function __construct($url = '', $name = 'itemForm', $o = null) {
        $this->setForm($this);
        $this->settings()->url($url)
                ->name($name)
                ->template('html')
                ->method('post')
                ->color('#FCD081');
//                ->security();
    }

    /* === компаненты формы === */

    // настройки
    public function settings() {
        return $this->register('cFormSettings');
    }

    // html
    public function html() {
        return $this->register('cFormHtml');
    }

    // обработка форм
    public function processing() {
        return $this->register('cFormProcessing');
    }

    // обновление форм
    public function update() {
        return $this->register('cFormUpdate');
    }

    public function hash($salt = '') {
        return 'f' . cHashing::hash($salt . $this->settings()->name . session_id());
    }

    public function name($salt = '') {
        return 'f' . cHashing::hash($salt . $this->settings()->name);
    }

    /* === /компаненты формы === */



    /* === Элементы формы === */

    private $mElement = array();

    public function add($name, $type) {
        if ($element = $this->config()->type()->$type) {
            $this->mElement[$name] = $this->register($element, cFormRegister::initialization);
            return $this->mElement[$name]->settings()->id($name);
        } else {
            throw new cFormException('нет такого элемента', $type);
        }
    }

    public function &all() {
        return $this->mElement;
    }

    public function is($name) {
        return isset($this->mElement[$name]);
    }

    public function &get($name) {
        if (isset($this->mElement[$name])) {
            return $this->mElement[$name];
        } else {
            throw new cFormException('отсутствует элемент формы:', $name);
        }
    }

    /* === /Элементы формы === */



    /* === управление элементами === */

    // очистка данных фотмы
    public function clear() {
        foreach ($this->all() as $object) {
            $object->clear();
        }
        $this->error()->clear();
    }

    //смена имени формы - новые данные для формы
    public function changeName($name) {
        $this->settings()->name($name);
        $this->clear();
    }

    // переопередеоение свойств формы
    public function reset() {
        foreach ($this->all() as $object) {
            $object->reset();
        }
        $this->error()->reset();
    }

    /* === /управление элементами === */



    /* === /интерфейс Iterator к foreach === */

    public function rewind() {
        reset($this->mElement);
    }

    public function current() {
        return null;
    }

    public function key() {
        return key($this->mElement);
    }

    public function next() {
        return next($this->mElement);
    }

    public function valid() {
        return current($this->mElement) !== false;
    }

    /* === /интерфейс Iterator к foreach === */



    /* === установка данных === */

    public function select($data) {
        foreach ($data as $name => $value) {
            if ($this->is($name)) {
                $this->get($name)->set($value);
            }
        }
    }

    /* === /установка данных === */




    /* === 464 === */
    /* === 464 === */
}

?>