<?php


class cmfSiteMapConfig {

    static public function menu() {
        $menu = array();
        $menu['index']   = 'Главная';
        $menu['news']    = 'Новости';
        $menu['contact'] = 'Контакты';
        $menu['catalog'] = 'Каталог';
        $menu['info']    = 'Информация';
		return $menu;
    }

}

?>
