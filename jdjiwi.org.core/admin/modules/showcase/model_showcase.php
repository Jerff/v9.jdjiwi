<?php


class model_showcase extends cmfDriverModel {

    const name = '/content/';
    static public function update($id=null) {
        cmfUpdateCache::update('showcase');
        self::updateUri();
	}


    static public function delete($id) {
	}


    static public function updateUri() {
        model_showcase_driver::updateUri(db_showcase_list);
	}

}

?>