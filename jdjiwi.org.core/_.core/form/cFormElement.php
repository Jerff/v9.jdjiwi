<?php

cLoader::library('form/element/cFormText');
cLoader::library('form/element/cFormEmail');
cLoader::library('form/element/cFormPassword');
cLoader::library('form/element/cFormNumber');
cLoader::library('form/element/cFormInt');
cLoader::library('form/element/cFormFloat');
cLoader::library('form/element/cFormRange');

//cLoader::library('form/element/cmfFormText');
//cLoader::library('form/element/cmfFormKcaptcha');
//cLoader::library('form/element/cmfFormPassword');
//cLoader::library('form/element/cmfFormTextarea');
//cLoader::library('form/element/cmfFormCheckbox');
//cLoader::library('form/element/cmfFormSelect');
//cLoader::library('form/element/cmfFormRadio');
//cLoader::library('form/element/cmfFormFile');
//cLoader::library('form/element/cmfFormImage');

cLoader::library('form/core/cFormElementReform');
cLoader::library('form/core/cFormElementFilter');
cLoader::library('form/library/cFormReform');
cLoader::library('form/library/cFormFilter');

abstract class cFormElement extends cFormCore {

    // настройки
    public function settings() {
        return $this->register('cFormSettings');
    }

    // преобразование значений
    public function reform() {
//        $this->isInit();
        return $this->register('cFormElementReform');
    }

    // преобразование значений
    public function filter() {
//        $this->isInit();
        return $this->register('cFormElementFilter');
    }

    // инициализация элемента
//    private function isInit() {
//        static $is = false;
//        if (!$is) {
//            $is = true;
//            $this->init();
//        }
//    }

    // инициализация элемента
    public function init() {

    }

    /* === управление данными === */

    private $value = null;

    // очистка данных фотмы
    public function clear() {
        $this->value = null;
    }

    // вернуть значения
    public function get() {
        return $this->value;
    }

    // вернуть значения формы - при пустом показываем значение по умолчанию
    public function value() {
        return $this->reform()->view($this->get());
//        return $this->value ? $this->reform()->view($this->value) : $this->settings()->default;
    }

    // установить значения
    public function set($value) {
        $this->value = $value;
    }

    // значение элемента не изменено со значения по умолчанию
    public function isDefault($value) {
        return $value == $this->settings()->default;
    }

    // переопередеоение свойств формы
    public function reset() {
        $this->clear();
    }

    /* === /управление данными === */



    /* === старое значение === */

    // id старого значение
    public function oldId() {
        return $this->form()->name('old' . $this->id());
    }

    /* === старое значение === */



    /* === идентификация === */

    // id ошибки
    public function errorId() {
        return $this->form()->name('error' . $this->id());
    }

    // идентификатор
    public function id() {
        return $this->form()->name('id') . '-' . $this->name();
    }

    // имя формы
    public function name() {
        return $this->settings()->id;
    }

    /* === /идентификация === */



    /* шаблоны */

    public function template() {
        return $this->register(
                        cLoader::template()->path(get_class($this) . '{Form}')
        );
    }

    public function error($attr = '') {
        $this->template()->error($this);
    }

    public function html($attr = '') {
        $this->template()->input($this, $attr);
        $this->template()->old($this);
    }

    /* === /шаблоны === */



    /* === обработка данных === */

    public function handler($isChange = false) {
        return $this->processing($isChange, false);
    }

    public function processing($isChange = true, $isUpload = true) {
        $value = cInput::post()->get($this->id());
        if ($this->isDefault($value)) {
            $value = null;
        }
        $value = $this->reform()->data($value);
        $this->filter()->start($value);

        if ($isChange) {
            if ($value == cInput::post()->get($this->oldId()))
                return null;
//            if($value===$this->getOld()) return null;
//            else if($value==$this->getOld()) return null;
        } else {
            if (is_null($value)) {
                $value = '';
            }
        }
        $this->set($value);
        return $value;
    }

    /* === /обработка данных === */



    /* === обновление данных === */

    public function update($isOldUpdate = true) {
        $this->template()->js($this, $isOldUpdate);
//        if ($isOldUpdate) {
//            cAjax::get()->script(
//                    cJScript::queryId($this->oldId())->val($this->get())
//            );
//        }
//        cAjax::get()->script(
//                cJScript::queryId($this->id())->val($this->value())
//        );
    }

    /* === /обновление данных === */
}

?>