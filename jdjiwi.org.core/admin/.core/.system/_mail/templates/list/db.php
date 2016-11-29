<?php


class _mail_templates_list_db extends driver_db_list {

	public function returnParent() {
		return '_mail_templates_edit_db';
	}

	protected function getTable() {
		return db_mail_templates;
	}

	protected function getSort() {
		return array('name');
	}

	protected function getWhereFilter() {
		$filter = array();

		$section = $this->getFilter('section');
		if($section) {
			$section = addslashes(urldecode($section));
			$filter[] = "name LIKE '%$section%'";
			$filter[] = 'AND';
		}
		$filter[] = 1;
		return $filter;
	}

	public function filterSection() {
		$res = $this->sql()->placeholder("SELECT DISTINCT(SUBSTRING_INDEX(name, ':', 1)) AS name FROM ?t ORDER BY ?o", $this->getTable(), $this->getSort());
		$filter = array();
		while($row=$res->fetchAssoc()) {
            $filter[($row['name'])] = array('name'=>$row['name']);
		}
		$res->free();
		return $filter;
	}

	public function getNameList($filter=null, $fileds=null, $isName=true) {
		$res = $this->sql()->placeholder("SELECT id, SUBSTRING_INDEX(name, ':', 1) AS `label`, name FROM ?t ORDER BY ?o", $this->getTable(), $this->getSort());
		$filter = array();
		while($row=$res->fetchAssoc()) {
            $filter[($row['id'])] = array('label'=>$row['label'], 'name'=>$row['name']);
		}
		$res->free();
		return $filter;
	}

}

?>