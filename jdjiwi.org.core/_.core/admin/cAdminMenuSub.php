<?php

class cAdminMenuSub {

    private $item = false;
    private $bakup = false;
    private $mType = array();
    private $select = false;

    // временная инициализация другим значением
    // сохранение истинное значения во временной переменной
    public function init($id) {
        $this->bakup = $this->getId();
        $this->set($id);
    }

    public function reset() {
        $this->setId($this->bakup);
    }

    public function setId($id) {
        $this->item = $id;
        cInput::get()->set('parentId', $id);
    }

    public function getId() {
        $str = $this->item ? $this->item : cInput::get()->get('parentId');
        return is_string($str) ? urldecode($str) : $str;
    }

    /* установки */

    // установить страницу в подменю
    static public function setType($page, $sel = false) {
        $this->mType[$page] = $sel;
    }

    // страница присуствует в подменю
    private function isType($page) {
        foreach ((array) $page as $p) {
            if (isset($this->mType[$p]))
                return true;
        }
        return false;
    }

    public function select($page) {
        $this->select = $page;
    }

    private function isSelect($page) {
        return $this->select === $page;
    }

    // id кеша
    private function сacheId() {
        return сString::serialize($this->mType);
    }

    public function &get() {
        if ($_menu = cAdmin::cache()->getMenu('subMenu' . $this->сacheId()))
            return $_menu;
        $_menu = $_subMenu = array();
        $_path = array(0, 0, 0);


        $main = cPages::getMain();
        $group = cAdmin::user()->getGroup();
        $res = cAccessModul::getModulOfPage($main);
        if ($res) {
            list($modul, $modulMenu, $_modulMenu) = $res;
            $sql = cRegister::sql();
            if ($modulMenu) {
                list($parent) = $sql->placeholder("SELECT id FROM ?t WHERE modul=? AND modulMenu=? AND type='list'", db_pages_admin, $modul, $modulMenu)
                        ->fetchRow();
            } else {
                list($parent) = $sql->placeholder("SELECT id FROM ?t WHERE modul=? AND type='list'", db_pages_admin, $modul)
                        ->fetchRow();
            }
            if ($parent) {

                $menuId = $selectId = $isSub = false;
                foreach ($_modulMenu as $k => $v) {
                    if (!empty($v['menu'])) {

                        $menuId = $k;
                        $_menu[$k] = array('name' => $v['menu'],
                            'length' => cString::strlen($v['menu']),
                            'url' => addslashes(cUrl::admin()->get($k)));
                        if ($main == $k) {
                            $_menu[$k]['sel'] = 1;
                            $selectId = $menuId;
                        }
                        $_subMenu[$menuId] = array();
                        if (!empty($v['submenu'])) {
                            $isSub = true;
                            $_subMenu[$menuId][$k] = array('name' => $v['submenu'],
                                'url' => addslashes(cUrl::admin()->get($k) .
                                        cUrlAdmin::requestUri(array('parentId' => $this->getId(), 'id' => null))));
                            if ($main == $k) {
                                $_subMenu[$menuId][$k]['sel'] = true;
                                if ($v['header'] !== 'false') {
                                    $header = empty($v['header']) ? 'редактировать' : $v['header'];
                                    if ($_menu[$menuId]['length'] < cString::strlen($header)) {
                                        $_menu[$menuId]['length'] = cString::strlen($header);
                                    }
                                    $_menu[$menuId]['sel'] = 2;
                                    $_menu[$menuId]['name'] .= " <br><small>{$header}</small>";
                                }
                            }
                        }
                    } else {
                        if ($main == $k) {
                            $selectId = $menuId;
                            if ($v['header'] !== 'false') {
                                $header = empty($v['header']) ? 'редактировать' : $v['header'];
                                $isSub = true;
                                if ($_menu[$menuId]['length'] < cString::strlen($header)) {
                                    $_menu[$menuId]['length'] = cString::strlen($header);
                                }
                                $_menu[$menuId]['sel'] = 2;
                                $_menu[$menuId]['name'] .= " <br><small>{$header}</small>";
                            }
                        }
                        if ($v['submenu']) {
                            if ($v['select'] and !$this->isType($v['select']))
                                continue;
                            $_subMenu[$menuId][$k] = array('name' => $v['submenu'],
                                'url' => addslashes(cUrl::admin()->get($k) .
                                        cUrlAdmin::requestUri(array('parentId' => $this->getId(), 'id' => null))));
                            if ($main == $k or ($v['select'] and $this->isSelect($k))) {
                                $_subMenu[$menuId][$k]['sel'] = true;
                            }
                        }
                    }
                }
                $_subMenu = ($isSub and isset($_subMenu[$selectId])) ? $_subMenu[$selectId] : array();


                while (1) {
                    array_unshift($_path, $parent);
                    $parent = $sql->placeholder("SELECT parent FROM ?t WHERE id=?", db_pages_admin, $parent)
                            ->fetchRow(0);
                    if (!$parent) {
                        break;
                    }
                }
            }
        }

        list($parent1, $parent2, $parent3) = $_path;
        $_menu = array($parent1, $parent2, $parent3, $_menu, $_subMenu);

        //cAdmin::cache()->setMenu('subMenu'. $this->сacheId(), $_menu);
        return $_menu;
    }

}

?>