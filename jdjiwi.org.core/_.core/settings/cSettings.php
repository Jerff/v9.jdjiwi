<?php


class cSettings {

	private static $value = array();

	private static function start() {
		static $is = false;
		if($is) return;
		$is = true;
		if(false===($value=cmfCache::get('config'))) {
            $res = cRegister::sql()->placeholder("SELECT id, data FROM ?t WHERE cache='yes' AND data!=''", db_sys_settings)
            							->fetchAssocAll();
            $value = array();
            foreach($res as $row) {
            	$value[$row['id']] = unserialize($row['data']);
            }
			cmfCache::set('config', $value, 'config');
		}
		self::$value = $value;
	}

	public static function get($part, $id=null) {
		self::start();
		return $id ? get2(self::$value, $part, $id) : get(self::$value, $part);
	}

	public static function read($part, $id=null) {
		$data = cRegister::sql()->placeholder("SELECT data FROM ?t WHERE id=?", db_sys_settings, $part)
            						->fetchRow(0);
		if(!$data) return $data;
		$data = unserialize($data);
		if(!$id) return $data;
		return get($data, $id);
	}

	public static function save($part, $send) {
		$data = cRegister::sql()->placeholder("SELECT data FROM ?t WHERE id=?", db_sys_settings, $part)
            						->fetchRow(0);
		if($data) {
		    $data = unserialize($data);
			foreach($send as $k=>$v)
				$data[$k] = $v;
	        $send = $data;
		}
		cRegister::sql()->add(db_sys_settings, array('data'=>serialize($send)), $part);
	}

}

?>