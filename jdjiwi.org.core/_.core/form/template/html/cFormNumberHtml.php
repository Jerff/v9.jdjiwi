<?php

cLoader::library('form/template/html/cFormTextHtml');

class cFormNumberHtml extends cFormTextHtml {

    public function attr(&$el, $attr) {
        $attr = $this->initAttr($el, $attr);
        if ($el->settings()->dataType) {
            $attr['data-type'] = $el->settings()->dataType;
        }
        if ($el->settings()->isNumber) {
            $attr['step'] = $el->settings()->isNumber;
        }

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