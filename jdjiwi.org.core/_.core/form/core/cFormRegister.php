<?php

abstract class cFormRegister {

    const personal = 1;
    const collective = 2;
    const initialization = 3;

    private $mPersonal = array();
    private $mCollective = array();
    private $form = false;
    private $parent = false;

//    public function __construct() {
//        cLog::log('__construct ' . get_class($this));
//    }
//
//    public function __destruct() {
//        cLog::log('__destruct ' . get_class($this));
//    }


    public function setForm(&$form) {
        $this->form = $form;
    }

    public function form() {
        return $this->form;
    }

    public function parent() {
        return $this->parent;
    }

    protected function &register($class, $type = self::personal) {
        switch ($type) {
            case self::personal:
                if (isset($this->mPersonal[$class])) {
                    return $this->mPersonal[$class];
                }
                $object = $this->mPersonal[$class] = new $class();
                break;

            case self::collective:
                if (isset($this->mCollective[$class])) {
                    return $this->mCollective[$class];
                }
                $object = $this->mCollective[$class] = new $class();
                break;

            case self::initialization:
                $object = new $class();
                break;

            default:
                throw new cException('нет такого типа данных', $type);
                break;
        }
        if (is_subclass_of($object, 'cFormRegister')) {
//            cLog::log(get_class($this) .' => '. $type .' => ' . print_r(array_keys($this->mCollective), true));
//            cLog::log("\t\t\t\t". get_class($this) .' => '. $type .' => ' . get_class($object));
//            cLog::log(print_r(array_keys($this->mCollective), true));
            $object->initRegister($this, $this->form, $this->mCollective);
        }
        return $object;
    }

    // инициализация регистра в подчиненных классах
    public function initRegister(&$parent, &$form, &$mCollective) {
        $this->parent = &$parent;
        $this->form = &$form;
        $this->mCollective = &$mCollective;
        if (is_subclass_of($this, 'cFormElement')) {
            $this->init();
        }
    }

}

?>