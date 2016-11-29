<?php
cLoader::library('form/template/html/cFormTextHtml');

class cFormPasswordHtml extends cFormTextHtml {

    public function html($el, $attr) {
        ?><input type="<?= $el->type() ?>" name="<?= $el->id() ?>" id="<?= $el->id() ?>" placeholder="<?= $el->settings()->default ?>" value="" <?= $attr ?> /><?
    }

    public function js($el, $isOldUpdate = true) {
        if ($isOldUpdate) {
            cAjax::get()->script(
                    cJScript::queryId($el->id())->val('')
            );
        }
    }

}
?>