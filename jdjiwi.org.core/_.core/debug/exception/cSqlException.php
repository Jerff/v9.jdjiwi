<?php

class cSqlException extends cException {

    protected $name = 'Ошибка базы данных';
    protected $sqlError = false;

    public function __construct($message, $error = '', $code = null, $previous=null) {
        parent::__construct($message, $code, $previous);
        $this->sqlError = implode(' ', $error);
    }

    protected function getCodeName() {
        return $this->sqlError;
    }

    public function __toString() {
        $code = $this->getCodeName();
        return '<b><big>' . $this->getName() . ':</big></b> '
                . (empty($code) ? '' : $code . ': ')
                . $this->getMessage()
                . PHP_EOL . self::parseTrace($this->getTraceAsString());
    }

}

?>