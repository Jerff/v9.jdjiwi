<?php

class cAdminMenu extends cPatternsRegistry {

    public function sub() {
        return $this->register('cAdminMenuSub');
    }

    public function &get() {
        if ($mMenu = cAdmin::cache()->getMenu('menu'))
            return $mMenu;
        $group = cAdmin::user()->getGroup();

        $main = cPages::getMain();
        $sql = cRegister::sql();

        $read = $sql->placeholder("SELECT p.id FROM ?t p LEFT JOIN ?t a ON(IF(p.modulMenu IS NULL, p.modul, CONCAT(p.modul, '/', p.modulMenu ))=a.modul OR p.modul = a.modul) WHERE p.modul IS NOT NULL AND a.group ?@ AND p.type IN('list') AND p.visible='yes'", db_pages_admin, db_access_read, $group)
                ->fetchRowAll(0, 0);
        $pages = cAccessModul::getListPages();

        $res = $sql->placeholder("SELECT id, parent, name, type, modul, modulMenu, isView FROM ?t WHERE visible='yes' AND type IN('tree', 'list') ORDER BY pos", db_pages_admin);
        $mMenu = array();
        while ($row = $res->fetchAssoc()) {
            $parent = $row['parent'];
            $id = $row['id'];
            $mMenu[$parent][$id]['name'] = $row['name'];
            if ($row['type'] === 'list') {
                if (!isset($read[$id]))
                    continue;
                if ($row['modulMenu'] and isset($pages[$row['modul']]) and is_array($pages[$row['modul']])) {
                    $url = get2($pages, $row['modul'], $row['modulMenu']);
                } else {
                    $url = get($pages, $row['modul']);
                }
                $mMenu[$parent][$id]['url'] = cUrl::admin()->get($url);
                if ($main === $url)
                    $mMenu[$parent][$id]['sel'] = true;
            } else {
                if ($row['isView'] === 'yes')
                    $mMenu[$parent][$id]['view'] = true;
            }
        }
        $res->free();

        $level1 = 0;
        self::tree($mMenu, $level1);

        cAdmin::cache()->setMenu('menu', $mMenu);
        return $mMenu;
    }

    static private function tree(&$mMenu, $level1, $level2 = 0, $parent = 0) {
        $level2++;
        $mUnset = array();
        $sel = false;
        foreach ($mMenu[$parent] as $key => &$value) {
            if ($isPages = isset($mMenu[$parent][$key]['url'])) {
                $value['url'] = $mMenu[$parent][$key]['url'];
                if (isset($mMenu[$parent][$key]['sel'])) {
                    $value['sel'] = $sel = $level1 = $level2;
                }
            }
            if ($isMenu = isset($mMenu[$key])) {
                list($sel_k, $unset_k) = self::tree($mMenu, $level1, $level2, $key);
                if ($sel_k) {
                    $value['sel'] = $sel = $level1;
                }
                $isMenu = $unset_k;
                if ($isMenu and !$isPages) {
                    $child = reset($mMenu[$key]);
                    $value['url'] = $child['url'];
                }
            }
            if (!$isMenu && !$isPages)
                $mUnset[] = $key;
        }
        foreach ($mUnset as $key) {
            unset($mMenu[$parent][$key]);
        }
        return array($sel, (bool) $mMenu[$parent]);
    }

}

?>