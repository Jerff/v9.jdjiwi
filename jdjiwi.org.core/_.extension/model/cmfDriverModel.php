<?php


abstract class cmfDriverModel {

    //abstract static public function update($id=null);
    //abstract static public function delete($id=null);
    //abstract static public function updateUri($id=null);
    //abstract static public function updateSearch($id=null);
    //abstract static public function updateCache($id=null);

/*    static public function isConf($s) {
        return strpos(static::conf, $s)!==false;
	}
    static public function update($id=null) {
        if(static::isConf('uri')) {
            static::updateUri();
        }
        if(static::isConf('search')) {
            static::updateSearch();
        }
        if(static::isConf('cache')) {
            static::updateCache();
        }
	}
    static public function delete($id) {
        if(static::isConf('uri')) {
            cmfContentUrl::delete(static::name, $id);
        }
        if(static::isConf('search')) {
            cmfContentUrl::delete(static::name, $id);
        }
        if(static::isConf('cache')) {
            cmfContentUrl::delete(static::name, $id);
        }
    }*/

    static public function updateWhere(&$where) {
        if(!is_array($where)) {
            if($where) {
                $where = array('id'=>$where);
            } else {
                $where = array(1);
            }
        } else {
            $k = array_keys($where);
            $v = array_values($where);
            if(!array_diff($k, $v)) {
                 $where = array('id'=>$where);
            }
        }
        return $where;
	}


    static protected function isUpdate($fields, $send) {
        if(!$send) return true;
        foreach(explode('|', $fields) as $k) {
			if(isset($send[$k])){
				return true;
			}
		}
		return false;
	}


}

?>