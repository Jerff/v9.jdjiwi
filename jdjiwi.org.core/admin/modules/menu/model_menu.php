<?php


class model_menu {

    static public function initMenu(&$form) {
        $form->addElement('menu', array('------', 'index'),        'Главная');
        $form->addElement('menu', array('------', 'news'),        'Новости');
        $form->addElement('menu', array('------', 'contact'),    'Контакты');
        $form->addElement('menu', array('------', 'adress'),    'Адресс');

        $name = cLoader::initModul('content_info_list_db')->getNameList();
        foreach($name as $key=>$value)
            $form->addElement('menu', array('Меню ', $key .'menu'), $value['name']);

        $name = cLoader::initModul('content_content_list_db')->getNameList();
        foreach($name as $key=>$value)
            $form->addElement('menu', array('Контент', $key .'content'), $value['name']);
    }

}

?>