<?php


class model_main extends cmfDriverModel {

    const name = '/content/';
    static public function update($id=null) {
        self::updateUri($id);
	}


    static public function delete($id) {
        cmfContentUrl::delete(self::name, $id);
	}


    static public function updateUri($where=null) {
	}

}

?>