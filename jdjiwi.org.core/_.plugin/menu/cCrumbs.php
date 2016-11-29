<?php

class cCrumbs {

    static private $crumbs = array();

    static public function add($name, $url = null) {
        self::$crumbs[$name] = $url;
    }

    static public function is() {
        return (bool) self::$crumbs;
    }

    static public function view() {
        if (!empty(self::$crumbs)) {
            cmfMenuView::viewSubMenu(cUrl::get('/index/'), self::$crumbs);
        }
    }

}

?>