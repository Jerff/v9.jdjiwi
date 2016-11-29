<?php

class cErrorException extends cException {

    protected $name = 'Ошибка';

    public function __construct($message, $errorLevel = 0, $errorFile = '', $errorLine = 0) {
        parent::__construct($message, false, $errorLevel);
        $this->file = $errorFile;
        $this->line = $errorLine;
    }

    protected function getCodeName() {
        switch ($this->getCode()) {
            case 1: return 'E_ERROR';
                break;
            case 2: return 'E_WARNING';
                break;
            case 4: return 'E_PARSE';
                break;
            case 8: return 'E_NOTICE';
                break;
            case 16: return 'E_CORE_ERROR';
                break;
            case 32: return 'E_CORE_WARNING';
                break;
            case 64: return 'E_COMPILE_ERROR';
                break;
            case 128: return 'E_COMPILE_WARNING';
                break;
            case 256: return 'E_USER_ERROR';
                break;
            case 512: return 'E_USER_WARNING';
                break;
            case 1024: return 'E_USER_NOTICE';
                break;
            case 2048: return 'E_STRICT';
                break;
            case 4096: return 'E_RECOVERABLE_ERROR';
                break;
            case 8192: return 'E_DEPRECATED';
                break;
            case 16384: return 'E_USER_DEPRECATED';
                break;
            case 30719: return 'E_ALL';
                break;
        }
    }

}

?>