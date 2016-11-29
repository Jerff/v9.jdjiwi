<?php


class model_baner extends cmfDriverModel {

    const name = '/baner/';
    static public function update($id=null) {
//        pre($id);
        cmfUpdateCache::update('baner');
        self::updateUri();
	}

    static public function delete($id) {
	}

    static public function updateUri() {
        model_showcase_driver::updateUri(db_baner);
	}

}

?>