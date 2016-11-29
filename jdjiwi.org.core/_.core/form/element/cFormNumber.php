<?php

cLoader::library('form/template/{form}/cFormNumber{Form}');

class cFormNumber extends cFormText {

    public function init() {
//        $this->setType('number');
        $this->settings()->dataType('number');
        $this->settings()->replace('step', 'isNumber');
        $this->settings()->isNumber(1);
        $this->settings()->strMax($this->config()->input()->max);
        $this->settings()->replace('min', 'numberMin');
        $this->settings()->replace('max', 'numberMax');
        $this->settings()->replace('range', 'numberRange');
    }

    public function attr(&$el, $attr) {
        $attr = $this->initAttr($el, $attr);
        if ($el->settings()->numberMin) {
            $attr['min'] = $el->settings()->numberMin;
        }
        if ($el->settings()->numberMax) {
            $attr['max'] = $el->settings()->numberMax;
        }
        if ($el->settings()->numberRange) {
            $range = $el->settings()->numberRange;
            if (isset($range[0]) and isset($range[1])) {
                list($attr['min'], $attr['max']) = $range;
            }
        }
        return $this->viewAttr($attr);
    }

}

?>