<?php

abstract class admin_registry_core {

    const personal = 1;
    const collective = 2;
    const initialization = 3;

    private $mPersonal = array();
    private $mCollective = array();
    private $parent = false;

    /* конструктор деструктор */

//    public function __construct() {
//        cLog::log('__construct: ' . get_class($this));
//    }
//
//    public function __destruct() {
//        cLog::log('__destruct: ' . get_class($this) . ($this->parent ? ' parent' . get_class($this->parent) : ''));
//    }

    /* /конструктор деструктор */

    public function parent() {
        return $this->parent;
    }

    /* === классы === */

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
        if (is_subclass_of($object, 'admin_registry_core')) {
//            pre(get_class($this) .' => '. $type, array_keys($this->mCollective));
            $object->initRegister($this, $this->mCollective);
        }
        return $object;
    }

    // инициализация регистра в подчиненных классах
    public function initRegister(&$parent, &$mCollective) {
        $this->parent = &$parent;
        $this->mCollective = &$mCollective;
        if (method_exists($this, 'init')) {
            $this->init();
        }
    }

    /* === /классы === */
}

?>