<?php

class cLoaderTemplate {

    private $form = 'html';
    private $page = 'default';

    public function setForm($mForm) {
        if (isset($mForm[cApplication])) {
            $this->form = $mForm[cApplication];
        }
    }

    public function setPage($mPage) {
        if (isset($mPage[cApplication])) {
            $this->page = $mPage[cApplication];
        }
    }

    public function path($file) {
        return str_replace(array('{form}', '{Form}', '{page}', '{Page}'), array($this->form, ucfirst($this->form), $this->page, ucfirst($this->page)), $file);
    }

}

?>