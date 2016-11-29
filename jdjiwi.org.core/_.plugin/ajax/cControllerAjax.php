<?php

cLoader::ajax();
cLoader::form();
cLoader::mail();
cLoader::library('patterns/cPatternsRegistry');
cLoader::library('ajax/cControllerAjaxSettings');
cLoader::library('ajax/cControllerAjaxForm');
//cLoader::library('ajax/cControllerAjaxUpdate');
cLoader::library('ajax/cControllerAjaxView');

class cControllerAjax extends cPatternsRegistry {
    /* === настройки === */

    // настройки
    public function settings() {
        return $this->register('cControllerAjaxSettings');
    }

    // формы
    public function form($id = false) {
        $form = $this->register('cControllerAjaxForm');
        if ($id) {
            return $form->get($id);
        } else {
            return $form;
        }
    }

    // html
    public function html() {
        return $this->register('cControllerAjaxView');
    }

//    // update
//    public function update() {
//        return $this->register('cControllerAjaxUpdate');
//    }

    /* === /настройки === */



    /* === создание объекта === */

    public function __construct($url = null, $count = 1, $jsFunc = 'core.ajax.sendForm') {
        $this->settings()
                ->url($url)
                ->jsFunc($jsFunc);
        $this->form()->set($count);
        $this->init();
    }

    protected function init() {

    }

    /* === /создание объекта === */



    /* === функции === */

    // хеш
    public function hash($salt = '') {
        return 'c' . cHashing::hash($salt . get_class($this) . $this->settings()->name);
    }

    /* === /функции === */



    /* === обработка данных === */

    // переопределяемая функция обработки форм
    protected function handler($index, &$isError, &$form) {
        $send = $form->processing()->handler();
        return $send;
    }

    protected function processing() {
        $isError = false;
        $data = array();
        foreach ($this->form()->all() as $index => $form) {
            $data[] = $this->handler($index, $isError, $form);
            $isError |= $form->error()->is();
        }
        if (empty($isError)) {
            return $this->form()->count() > 1 ? $data : $data[0];
        }
        $this->update();
    }

    // обновить данные формы
    protected function update($error = false) {
        foreach ($this->form()->all() as $form) {
            $form->update()->js(false);
        }
        if (empty($error)) {
            cAjax::get()->script(
                    cJScript::queryId($this->html()->errorId())->hide()
            );
        }
        $this->scroll();
        exit;
    }

    // scroll
    protected function scroll() {
//        cAjax::get()->script('core.ajax.controller.scroll("' . $this->html()->id() . '", "' . $this->html()->scrollId() . '");');
    }

    // показать ошибку
    protected function error($error = false) {
        if (!empty($error)) {
            cAjax::get()->script(
                    cJScript::queryId($this->html()->errorId())->html($error)->show()
            );
            cAjax::get()->script('core.ajax.controller.error("' . cJScript::quote($error) . '");');
        }
        $this->update(true);
    }

    // показать ошибку
    public function success($message, $title = '', $text = '') {
        if (empty($title)) {
            $title = $message;
        }
        cAjax::get()->script('core.ajax.controller.success("' . cJScript::quote($title) . '", "' . cJScript::quote($text) . '");');
        cAjax::get()->htmlId($this->html()->id(), '<b>' . $message . '</b>');
        $this->scroll();
    }

    /* === /обработка данных === */
}

?>