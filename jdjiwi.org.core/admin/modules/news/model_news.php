<?php

class model_news extends cmfDriverModel {

    const name = '/news/';

    static public function update($id) {
        cmfUpdateCache::update('news');
    }

    static private function getPagesList() {
        return array(
            'news',
            'articles',
            'photos'
        );
    }

    static public function getInfoList() {
        return cRegister::sql()->placeholder("SELECT id, name FROM ?t WHERE pages ?@", db_content_info, self::getPagesList())
            ->fetchRowAll(0, 1);
    }

    static public function delete($id) {

    }

}

?>