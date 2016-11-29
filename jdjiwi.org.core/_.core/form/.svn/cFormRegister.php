<?php

class cFormRegister {

    private $mRegister = array();
    protected function &register($class) {
        if (isset($this->mRegister[$class])) {
            return $this->mRegister[$class];
        } else {
            $this->mRegister[$class] = new $class();
            if (is_subclass_of($this->mRegister[$class], 'cFormRegister')) {
                $this->initRegister($this->mRegister[$class]);
            }
            return $this->mRegister[$class];
        }
    }

    // инициализация регистра в подчиненных классах
    protected function initRegister(&$object) {
        $object->setRegister($this, $this->mRegister);
    }

    public function setRegister(&$parent, &$mRegister) {
        $this->mRegister = &$mRegister;
        $this->parent = &$parent;
    }

    protected function parent() {
        return $this->parent;
    }

}

?>