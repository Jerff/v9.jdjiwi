<?php

class model_content_info extends cmfDriverModel {

    const name = '/info/';

    static public function update($id = null) {
        self::updateUri($id);
    }

    static public function getListPage() {
        return array(
            'default' => 'Обычная страница',
            'news' => 'Новости',
            'photo' => 'Фоторепортажи',
            'subscribe' => 'Рассылка',
            'search' => 'Поиск',
            'faq' => 'FAQ'
        );
    }

    static public function getViewMenu() {
        return array(
            'none' => 'Обычное',
            'right' => 'Показывать справо'
        );
    }

    static public function delete($id) {
        cmfContentUrl::delete(self::name, $id);
    }

    static public function updateUri($where = null) {
        self::updateWhere($where);
        cRegister::sql()->placeholder("
                REPLACE ?t SELECT ?, id, 0, 0, isUri FROM ?t WHERE ?w", db_content_url, self::name, db_content_info, $where);
    }

}

?>