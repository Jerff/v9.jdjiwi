<?php

abstract class cPatternsRegistry {

    private $mRegister = array();
    private $parent = false;

    protected function &register($class) {
        if (!isset($this->mRegister[$class])) {
            $this->mRegister[$class] = new $class();
            if (is_subclass_of($this->mRegister[$class], 'cPatternsRegistry')) {
                $this->mRegister[$class]->initParent($this);
            }
        }
        return $this->mRegister[$class];
    }

    // инициализация регистра в подчиненных классах
    public function initParent(&$parent) {
        $this->parent = $parent;
    }

    protected function parent() {
        return $this->parent;
    }

}

?>