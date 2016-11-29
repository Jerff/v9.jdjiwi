<?php


class cmfSearchData {

    static public function reformStr($str) {
        $str = strip_tags($str);
        $str = preg_replace('~([^a-zа-я0-9])~iu', ' ', $str);
		$str = preg_replace('~\s{2,}~', ' ', $str);
        $str = trim($str);
		$str = substr($str, 0, 50000);
		return $str;
	}




    static public function update() {
        model_product::updateSearch();
	}


    static public function updateWhere(&$where) {
        if(!is_array($where)) {
            if($where) {
                $where = array('id'=>$where);
            } else {
                $where = array(1);
            }
        }
        return $where;
	}
    public static function delete($page, $where) {
        self::updateWhere($where);
        cRegister::sql()->del(db_search, $where);
	}

}

?>