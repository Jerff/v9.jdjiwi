<?php

class cAdminRouter extends admin_core {
    /* === старт страницы === */

    private function assing($name, $value) {
        cAdmin::template()->assing($name, $value);
    }

    public function start($path) {
        require($path . '.php');
    }

    /* === /старт страницы === */



    /* === управление комнтроллерами === */

    private $mController = array();

    private function &load($name, $controller, $id = null) {
        if (!class_exists($controller)) {
            cLoader::modul($controller);
        }
        $this->mController[$name] = new $controller($id);
        return $this->mController[$name];
    }

    private function &all() {
        return $this->mController;
    }

    private function processing() {
        if($this->command()->isNoRecord()) {
            return;
        }
        $mController = $this->all();

        cInput::get()->initBackup();
        if (cInput::post()->is('isAjaxCall')) {
            cAjax::start();
            $name = (string) cInput::post()->get('ajaxName');
            if (isset($mController[$name])) {
                $arg = (string) cInput::post()->get('ajaxArg');
                $arg = json_decode($arg);
                if (cInput::post()->is('save')) {
                    $arg[] = 'save';
                }

                cAccess::isRead($mController[$name]);
                $mController[$name]->ajaxCall($arg);
                cInput::get()->reset();
            }
            if (!$this->command()->isNewView()) {
                exit;
            }
        }
        foreach ($mController as $name=>$controller) {
            cAccess::isRead($controller);
            $controller->start();
            $this->assing($name, $controller);
        }
    }

}

?>