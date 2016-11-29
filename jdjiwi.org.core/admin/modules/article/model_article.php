<?php


class model_article extends cmfDriverModel {


    const name = '/article/';
    static public function update($id) {
        cmfUpdateCache::update('article');
	}

    static public function delete($id) {
	}

}

?>