<?php

abstract class cFormTemplateHtml {

    public function error($el) {
        ?><span class="formElementError" id="<?= $el->errorId() ?>"></span><?
    }

    public function input(&$el, $attr) {
        if ($el->settings()->isErrorHide) {
            echo $this->html($el, $attr);
        } else {
            ?><span class="formElement"><?= $this->html($el, $attr) ?></span><?
        }
    }

    // инициализация атибутов
    public function initAttr(&$el, $attr) {
        if (is_array($attr)) {
            $attr = array($attr);
        }
        $attr = array($attr);
//        if ($el->settings()->readonly) {
//            $attr['readonly'] = 'readonly';
//        }
//        if ($el->settings()->isNoEmpty) {
//            $attr['required'] = 'required';
//        }
//        if ($el->settings()->pattern) {
//            $attr['pattern'] = $el->settings()->strMax;
//        }
//        if ($el->settings()->disabled) {
//            $attr['disabled'] = 'disabled';
//        }
        return $attr;
    }

    // вывод атибутов
    public function viewAttr($attr) {
        $str = '';
        foreach ($attr as $key => $value) {
            if ($key) {
                $str .= ' ' . $key . '="' . $value . '"';
            } else {
                $str .= ' ' . $value;
            }
        }
        return $str;
    }

    abstract public function html($el, $attr);

    public function old($el) {
        ?><input type="hidden" name="<?= $el->oldId() ?>" id="<?= $el->oldId() ?>" value="<?= cString::specialchars($el->get()) ?>" /><?
    }

    public function js($el, $isOldUpdate = true) {
        if ($isOldUpdate) {
            cAjax::get()->script(
                    cJScript::queryId($el->oldId())->val($el->get())
            );
        }
        cAjax::get()->script(
                cJScript::queryId($el->id())->val($el->value())
        );
    }

}
?>