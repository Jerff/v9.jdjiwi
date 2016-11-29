<?php


class user_list_db extends driver_db_list {

	public function returnParent() {
		return 'user_edit_db';
	}

	protected function getTable() {
		return db_user;
	}

	protected function getFields() {
		return array('id', 'name', 'login', 'register', 'visible', 'banDate');
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
		$where = array();

		if($name = $this->getFilter('name')) {
			$where[] = $this->sql()->getQuery("name LIKE '%?s%'", urldecode($name));
			$where[] = 'AND';
		}

		if($email = $this->getFilter('email')) {
			$where[] = $this->sql()->getQuery("email LIKE '%?s%'", urldecode($email));
			$where[] = 'AND';
		}
		$where[] = "(`admin` IS NULL)";
		return $where;
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

	public function getNameList($filter=null, $fileds=null, $isName=true) {
		if($filter) $filter[] = 'AND';
        $filter['visible'] = 'yes';
        $filter[] = 'AND';
        $filter['register'] = 'yes';

        $fileds[] = 'family';

		$mUser = parent::getNameList($filter, $fileds, $isName);
        foreach($mUser as &$row) {
            $row['name'] = cmfUser::generateName($row);
        }
        return $mUser;
	}

}

?>