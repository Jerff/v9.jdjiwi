<?php

class cControllerAjaxView extends cPatternsRegistry {
    /* === форма === */

    // id блока
    public function id() {
        return $this->parent()->hash('form-id');
    }

    // id формы
    public function formId() {
        return $this->parent()->hash('form-item');
    }

    // начало контроллера
    public function start($attr = '') {
        ?>
        <div id="<?= $this->id() ?>">
            <form id="<?= $this->formId() ?>" action="" name="<?= $this->formId() ?>" enctype="multipart/form-data" method="post" <?= $attr ?>>
                <script type="text/javascript">
                    $('#<?= $this->formId() ?>').submit(function() {
                        return <?= $this->parent()->settings()->jsFunc ?>('<?= $this->parent()->settings()->url ?>', this);
                    });
                </script><?
        foreach ($this->parent()->form()->all() as $form) {
            $form->html()->start();
        }
    }

    // конец контроллера
    public function end() {
        foreach ($this->parent()->form()->all() as $form) {
            $form->html()->end();
        }
        ?></form>
        </div><?
    }

    /* === /форма === */



    /* === ошибки === */

    // id сообщения ошибки
    public function errorId() {
        return $this->parent()->hash('form-error');
    }

    // показ ошибок
    public function error($attr = '') {
        ?><p class="formAjaxError" id="<?= $this->errorId() ?>" <?= $attr ?>><b>Форма не отправлена!</b></p><?
    }

    /* === /ошибки === */



    /* === сохранение === */

    // id сообщения сохранения
    public function saveId() {
        return $this->parent()->hash('form-save');
    }

    // показ сохранения
    public function save($attr = '') {
        ?><p class="formAjaxSave" id="<?= $this->saveId() ?>" <?= $attr ?>><b>Данные сохранены</b></p><?
    }

    /* === /сохранение === */



    /* === ошибки === */

    // id скролла
    public function scrollId() {
        return $this->parent()->hash('form-scroll');
    }

    // скоролл
    public function scroll() {
        ?><div id="<?= $this->scrollId() ?>" class="core-ajax-scroll"></div><?
    }

    /* === скролл === */
}
?>