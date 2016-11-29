<?php

cLoader::library('user/model/cmfDriverUser');
class cmfAdminModel extends cmfDriverUser {


	static private function getWhere() {
		return array("(LENGTH(`admin`)>1)");
	}

	public static function isAdmin($id) {
		return self::isUserWhere($id, self::getWhere());
	}

	static public function accesIs($id) {
		if(!self::isAdmin($id)) exit;
	}


	static public function save($send, $id=0) {
        if(!$id) {
			$send['register']='yes';
		}
		if(isset($send['admin'])) {
			if(!$send['admin']) {
                $send['admin'] = '[0]';
			}
		}
		return parent::save($send, $id);
	}

	public function getFieldsParam() {
		return array('shop');
	}

}

?>