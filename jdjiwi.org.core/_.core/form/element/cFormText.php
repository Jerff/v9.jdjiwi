<?php

cLoader::library('form/template/{form}/cFormText{Form}');

class cFormText extends cFormElement {

    private $type = 'text';

    public function type() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function init() {
        $this->settings()->strMax($this->config()->input()->max);
        $this->settings()->replace('min', 'strMin');
        $this->settings()->replace('max', 'strMax');
        $this->settings()->replace('range', 'strRange');

//        $this->reform()->add('isFloat');
//        $this->filter()->add('intRange', $this->settings()->min, $this->settings()->max);
//        $this->filter()->add('intMin', 46346346);
//        $this->filter()->add('intMax', $this->settings()->max);
//        $this->filter()->add('strMin', 346);
//        $this->filter()->add('strMax', $this->settings()->max);
    }

}

?>