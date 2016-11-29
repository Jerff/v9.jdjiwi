<?php

abstract class controller_ajax_core extends admin_core {

    private $isCall = false;
    private $isUpdate = false;

    protected function isCall() {
        return $this->isCall;
    }

    public function ajaxCall($arg) {
        $this->isCall = true;

        $func = array_shift($arg);
        $isUpdate = $this->access()->isCall($func);
        if ($isUpdate) {
            $this->setUpdate();
        }
        if (method_exists($this, $func)) {
            call_user_func_array(array(&$this, $func), $arg);
        } else if (method_exists($this->getModul(), $func)) {
            call_user_func_array(array($this->getModul(), $func), $arg);
        }
        if ($isUpdate) {
            $this->setNoUpdate();
        }
        $this->isCall = false;
    }

    abstract protected function update();

    protected function isUpdate() {
        return $this->isUpdate;
    }

    protected function setUpdate() {
        $this->isUpdate = true;
    }

    protected function setNoUpdate() {
        foreach ($this->modulAll() as $modul) {
            if ($modul->getDb()) {
                $modul->getDb()->update()->start();
            }
        }
        $this->html()->update();
        $this->siteUrl()->update();
        $this->isUpdate = false;
    }

}

?>