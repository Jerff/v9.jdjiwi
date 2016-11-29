<?php


class model_content_pages extends cmfDriverModel {


    const name = '/content/pages/';
    public static function typeList() {
        $param = array();
        $param['header']['name'] = 'Текст вверху сайта';
        return $param;
    }


    static public function update($id) {
	}

    static public function delete($id) {
	}

}

?>