<?php


class model_photo extends cmfDriverModel {


    const name = '/photo/';
    static public function update($id) {
        cmfUpdateCache::update('photo');
	}

    static public function delete($id) {
	}

}

?>