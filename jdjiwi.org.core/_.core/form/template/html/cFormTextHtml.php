<?php
cLoader::library('form/template/cFormTemplateHtml');

class cFormTextHtml extends cFormTemplateHtml {

    public function attr(&$el, $attr) {
        $attr = $this->initAttr($el, $attr);
        if ($el->settings()->strMax) {
            $attr['maxlength'] = $el->settings()->strMax;
        }
        return $this->viewAttr($attr);
    }

    public function html($el, $attr) {
        //placeholder вместо data-default
        ?><input type="<?= $el->type() ?>" name="<?= $el->id() ?>" id="<?= $el->id() ?>" placeholder="<?= $el->settings()->default ?>" value="<?= cString::specialchars($el->value()) ?>" <?= $this->attr($el, $attr) ?> /><?
    }

}
?>