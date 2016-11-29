<?php

class cPagesBase {

    private $mBase = null;

    public function set($mBase) {
        $this->mBase = $mBase;
    }

    public function __get($name) {
        return isset($this->mBase[$name]) ? $this->mBase[$name] : null;
    }

    public function router() {
        // выбираем раздел сайта
        switch (cApplication) {
            case 'application':
            case 'cron':
            case 'ajax':
            case 'compileJsCss':
                return cAppHostUrl;
                break;

            case 'admin':
                return cAdminHostUrl;
                break;

            default:
                exit;
        }
    }

}

?>
