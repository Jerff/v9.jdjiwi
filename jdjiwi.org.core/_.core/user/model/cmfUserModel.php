<?php

cLoader::library('user/model/cmfDriverUser');
class cmfUserModel extends cmfDriverUser {


	static private function getWhere() {
		return array("(`admin` IS NULL)");
	}

	static public function isUser($id) {
		return self::isUserWhere($id, self::getWhere());
	}
	static public function accesIs($id) {
		if(!self::isUser($id)) exit;
	}

 	static public function getLogin($login){
		return self::getLoginWhere($login, self::getWhere());
	}

	static public function changePassword($login, $cod){
		return self::changePasswordWhere($login, $cod, self::getWhere());
	}


	static protected function getFieldsParam() {
		return array('discount', 'userBasket', 'userPay');
	}
    static protected function getDataParam($id) {
        $row = cRegister::sql()->placeholder("SELECT data, ?fields FROM ?t WHERE id=?", self::getFieldsParam(), db_user_data, $id)
                                        ->fetchAssoc();
        if($row) {
            foreach(unserialize($row['data']) as $k=>$v) {
                if(!isset($row[$k])) $row[$k] = $v;
			}
			unset($row['data']);
			return $row;
		} else {
			return array();
		}
	}
	static public function saveParam($send, $id) {
		$save = array();
    	foreach(self::getFieldsParam() as $k) {
            if(isset($send[$k])) {
                $save[$k] = $send[$k];
                unset($send[$k]);
            }
    	}
		if($id) {
			$data = self::getDataParam($id);
			if($data) {
				foreach($send as $k=>$v) {
					if(!isset($fields)) {
					    $data[$k] = $v;
		            }
            	}
				$send = $data;
			}
		}
		$save['data'] = serialize($send);
		cRegister::sql()->add(db_user_data, $save, array('id'=>$id, 'AND', '1'));
	}

}

?>