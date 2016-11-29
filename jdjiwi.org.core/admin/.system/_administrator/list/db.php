<?php


class _administrator_list_db extends driver_db_list {

	public function returnParent() {
		return '_administrator_edit_db';
	}

	protected function getTable() {
		return db_user;
	}

	protected function getFields() {
		return array('id', 'name', 'login', 'visible', 'banDate');
	}
	protected function getFieldsSes() {
		return array('sesIp', 'sesProxy', 'sesDate');
	}

	protected function getSort() {
		return array('login');
	}

	protected function runListQuery($id=null, $offset=null, $limit=null) {
		if(is_null($id)) {
			return $this->sql()->placeholder("SELECT SQL_CALC_FOUND_ROWS ?fields:u, ?fields:s FROM ?t u LEFT JOIN ?t s ON(u.id=s.id) WHERE ?w:u ORDER BY ?o:u LIMIT ?i, ?i ", $this->getFields(), $this->getFieldsSes(), $this->getTable(), db_user_ses, $this->getWhereFilter(), $this->getSort(), $offset, $limit);
		}
		else {
			if(empty($id)) return false;
			return $this->sql()->placeholder("SELECT ?fields:u, ?fields:s FROM ?t u LEFT JOIN ?t s ON(u.id=s.id) WHERE ?w:u", $this->getFields(), $this->getFieldsSes(), $this->getTable(), db_user_ses, $this->getWhereId($id)); // AND ?w, $this->filter()
		}
	}

	protected function getWhereFilter() {
		$admin = (int)$this->getFilter('admin');
		switch($admin) {
			case 0:
				return array("(LENGTH(`admin`)>1)");

			case -1:
				return array("`admin`='[0]'");

            default:
            	return array("`admin` LIKE '%[$admin]%'");
		}
	}

	public function loadData(&$row) {
		$row['sesIp'] = long2ip($row['sesIp']);
		$row['sesProxy'] = long2ip($row['sesProxy']);
		$row['sesDate'] = date("d.m.y H:i:s", strtotime($row['sesDate']));

		if(strtotime($row['banDate']) > time()) {
			$row['ban'] = date("d.m.y H:i:s", strtotime($row['banDate']));
		}

		parent::loadData($row);
	}

}

?>