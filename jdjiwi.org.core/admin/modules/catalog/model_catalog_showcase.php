<?php


class model_catalog_showcase extends cmfDriverModel {

    const name = '/section/showcase/';
    static public function update($id=null) {
        cmfUpdateCache::update('section.showcase');
        self::updateUri();
	}

    static public function delete($id) {
	}

    static public function updateUri() {
        model_showcase_driver::updateUri(db_section_shop);
	}

}

?>