<?php

class cAccess {

    static public function noAccess($message = 'Вам отказано в доступе на просмотр данной страницы') {
        if (cAjax::get()) {
            cAjax::get()->alert($message);
        } else {
            echo $message;
        }
//        throw new cException('отказано в доступе');
        exit;
    }

    static public function isRead(&$controller) {
        cDebug::sqlOff();
        $read = cRegister::sql()->placeholder("SELECT `modul` FROM ?t WHERE `group` ?@", db_access_read, cAdmin::user()->getGroup())
                ->fetchRowAll(0, 0);
        if (!self::isAccess($read, $controller)) {
            self::noAccess();
        }
        cDebug::sqlOn();
    }

    static public function isControllerRead(&$controller) {
        self::noAccess('Вам отказано в доступе на чтение данных');
    }

    static public function isWrite(&$controller) {
        cDebug::sqlOff();
        $write = cRegister::sql()->placeholder("SELECT `modul` FROM ?t WHERE `group` ?@", db_access_write, cAdmin::user()->getGroup())
                ->fetchRowAll(0, 0);
        if (!self::isAccess($write, $controller)) {
            self::noAccess('Вам отказано в доступе на изменения данных страницы');
        }
        cDebug::sqlOn();
    }

    static public function isDelete(&$controller) {
        cDebug::sqlOff();
        $write = cRegister::sql()->placeholder("SELECT `modul` FROM ?t WHERE `group` ?@", db_access_delete, cAdmin::user()->getGroup())
                ->fetchRowAll(0, 0);
        if (!self::isAccess($write, $controller)) {
            self::noAccess('Вам отказано в доступе на удаление данных');
        }
        cDebug::sqlOn();
    }

    static public function isAccess($read, &$controller) {
        $name = get_class($controller);
        list($mModul, $mRules, $mController) = self::getRules();
        if (!isset($mController[$name]))
            return false;
        $rule = $mController[$name];
        if (!$rule)
            return true;
        if (!empty($mRules[$rule]['modul'])) {
            $modul = $mRules[$rule]['modul'];
            if (isset($read[$modul]))
                return true;
            if (isset($read[$modul . '/' . $rule]))
                return true;
            return false;
        }
        return self::isAccessTree($rule, $controller->getId(), $read, $controller, $mModul, $mRules, $mController);
    }

    static public function isAccessTree($rule, $id, $read, &$controller, &$_modul, &$mRules, &$_controller) {
        if (!isset($mRules[$rule]['rules']))
            return false;
        $rules = $mRules[$rule]['rules'];
        foreach ($rules as $name => $rule) {
            $where = get($rule, 'where', array());
            if ($controller->access()->isRule($where)) {
                if ($id) {

                } else {

                }
                if (!isset($mRules[$name]))
                    return false;
                if (!empty($mRules[$name]['modul'])) {
                    $modul = $mRules[$name]['modul'];
                    if (isset($read[$modul]))
                        return true;
                    if (isset($read[$modul . '/' . $rule]))
                        return true;
                    return false;
                }
                return self::isAccessTree($rule, $id, $read, $controller, $_modul, $mRules, $_controller);
            }
        }
        return false;
    }

    static private function getRules() {
        if (!$mRules = cAdmin::cache()->get('cmfAccess::getRules')) {
            $mRules = $mController = $mModul = array();
            foreach (cAccessModul::rulesFiles() as $f) {
                $xml = new SimpleXMLElement(file_get_contents($f));
                $path = preg_replace('~.*\/((_?\w+)\/rules\.xml)~', '$2', $f) . '/';
                foreach ($xml->modul as $modul) {
                    $modulId = (string) $modul->attributes()->id;
                    if ($modul->rules) {
                        foreach ($modul->rules->rule as $rule) {
                            $ruleId = (string) $rule->attributes()->id;
                            $mModul[$modulId][$ruleId] = $ruleId;
                            $mRules[$ruleId]['modul'] = $modulId;
                            $mRules[$ruleId]['object'] = (string) $rule->attributes()->object === true;
                            foreach ($rule->elements->element as $element) {
                                $controller = str_replace('/', '_', $path . (string) $element . '/controller');
                                $mRules[$ruleId]['controller'][] = $controller;
                                $mController[$controller] = $ruleId;
                            }
                            if ($rule->childs)
                                foreach ($rule->childs->child as $child) {
                                    $childId = (string) $child;
                                    $rule = array('parent' => (string) $child->attributes()->parentId);
                                    foreach ($child->attributes() as $k => $v) {
                                        if ($k != 'parentId')
                                            $rule['where'][$k] = (string) $v;
                                    }
                                    $mRules[$childId]['rules'][$ruleId] = $rule;
                                }
                        }
                    } else {
                        $controller = str_replace('/', '_', $path . 'controller');
                        ;
                        $mController[$controller] = false;
                    }
                }
            }

            $mRules = array($mModul, $mRules, $mController);
//            cAdmin::cache()->set('cmfAccess::getRules', $mRules, 'access');
        }
        return $mRules;
    }

}

?>