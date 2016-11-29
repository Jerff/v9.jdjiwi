<?php


class model_subscribe extends cmfDriverModel {


    const name = '/subscribe/';
    public static function typeList() {
        $param = array();
        foreach(cmfSubscribe::typeList() as $k=>$v) {
            $param[$k]['name'] = $v;
		}
        return $param;
    }

    static public function update($id) {
	}

    static public function delete($id) {
	}

}

?>