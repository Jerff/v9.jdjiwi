<?php

class html_edit_core extends admin_registry_core {

    private $name = null;

    public function startForm($jsName = 'modul') {
        cInput::get()->reset();
        $this->setJsName($jsName);
        return view_edit::startForm(
                        $this->parent()->hash('form' . $jsName), $this->errorId(), $this->parent()->url()->getSubmit(), $jsName
        );
    }

    public function endForm() {
        return view_edit::endForm($this->jsName());
    }

    protected function setJsName($n) {
        $this->name = $n;
    }

    protected function jsName() {
        return $this->name;
    }

    protected function errorId() {
        return $this->parent()->hash('error');
    }

    public function listId() {
        return $this->parent()->hash('list-id');
    }

    protected function ajaxJsName() {
        return cInput::post()->get('ajaxJsName');
    }

    protected function update() {

    }

}

?>