<?php

class appUrl_core extends admin_registry_core {

    private $func = false;

    public function init($func) {
        $this->func = $func;
    }

    protected function data() {
        //сделать вызов данных от контроллера
        switch ($this->parent()->type()) {
            case 'edit':
                return $data = array();
                break;

            case 'tree':
                return $data = array();
                break;

            case 'tree':
                return $data = array();
                break;

            default:
                break;
        }
    }

    public function get($data = array()) {
        if ($this->parent()->id()->isEmpty())
            return false;
        $init = $this->func;
        $arg = $init(new cmfData($this->data()), $this->filter());
        return cUrl::param($arg);
    }

    public function item($id = null) {
        if ($id)
            $this->id()->init($id);
        $html = view_siteUrl::html(
                        $this->parent()->hash(), $this->parent()->type(), $this->get()
        );
        if ($id)
            $this->id()->reset();
        return $html;
    }

    protected function update() {
        view_siteUrl::jsUpdate(
                $this->ajax(), $this->parent()->hash(), $this->parent()->type(), $this->get()
        );
    }

}

?>