<?php

class news_edit_modul extends driver_modul_edit {

    protected function init() {
        parent::init();

        $this->setDb('news_edit_db');

        // формы
        $form = $this->form()->get();
        $form->add('date', new cmfFormTextDateTime());

        $form->add('uri', new cFormText(array('uri' => 95)));
        $form->add('header', new cmfFormTextarea(array('max' => 255, '!empty')));
        $form->add('notice', new cmfFormTextareaWysiwyng('news', $this->id()->get()));
        $form->add('content', new cmfFormTextareaWysiwyng('news', $this->id()->get()));
        $form->add('visible', new cmfFormCheckbox());

        $form->add('title', new cmfFormTextarea(array('max' => 500)));
        $form->add('keywords', new cmfFormTextarea(array('max' => 500)));
        $form->add('description', new cmfFormTextarea(array('max' => 500)));
    }

    protected function saveStart(&$send) {
        parent::saveStart($send);
        if (isset($send['notice']) and empty($send['notice'])) {
            $notice = $this->form()->get()->handlerElement('content');
            $send['notice'] = cString::subContent($notice);
        }

        parent::saveStartUri($send, 'header', 95);
    }

}

?>