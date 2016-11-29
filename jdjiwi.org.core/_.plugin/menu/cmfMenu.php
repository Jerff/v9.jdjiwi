<?php


cLoader::library('menu/cmfMenuView');
cLoader::library('menu/cmfMenuConfig');
class cmfMenu {

    static private $menu = array();
    static public function add($name, $url=null) {
        self::$menu[$name] = $url;
    }

    static private $select = '';
    static public function setSelect($id, $value) {
        self::$select[$id] = $value;
    }
    static public function select($id, &$value) {
        if(!isset(self::$select[$id])) return;
        if(isset($value[self::$select[$id]])) {
        	$value[self::$select[$id]]['sel'] = 1;
        }
    }


    static public function isSubMenu() {
        return (bool)self::$menu;
    }

    static public function viewSubMenu() {
        if(self::$menu) {
            cmfMenuView::viewSubMenu(cUrl::get('/index/'), self::$menu);
        }
    }


    static public function getFooter() {
	    $res = cRegister::sql()->placeholder("SELECT menu, name, url FROM ?t WHERE visible='yes' ORDER BY pos", db_menu)
								    ->fetchRowAll();
		$_menu = array();
	    foreach($res as $row) {
	        self::getUrl($_menu, $row);
	    }
	    return $_menu;
    }

    static private function getUrl(&$_menu, $row) {
        list($id, $name, $url) = $row;
        $res = cmfMenuConfig::getUrl($id);
        if($res) {
            if(isset($res[$id])) {
                foreach($res as $k=>$v) {
                    $_menu[$id . $k] = $v;
                }
            } else {
                if(!empty($name)) $res['name'] = $name;
                if($id==='adress') {
                    $res['url'] = $url;
                    $_menu[$url] = $res;
                } else {
                    $_menu[$id] = $res;
                }
            }
        }
    }

}

?>