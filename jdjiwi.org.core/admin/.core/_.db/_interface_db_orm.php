<?php


abstract class _interface_db_orm extends driver_interface {

	// --------------- driver_db_edit ---------------
	// --------------- получение данных из записей ---------------
	public function getListId($where=array(1), $id='id') {
		return $this->sql()->placeholder("SELECT ?field FROM ?t WHERE ?w", $id, $this->getTable(), $where)
									->fetchRowAll(0, 0);
	}

	public function getDataId($id) {
		return $this->sql()->placeholder("SELECT * FROM ?t WHERE ?w", $this->getTable(), $this->getWhereId($id))
									->fetchAssoc();
	}

	public function getFeildOfId($field, $id) {
		return $this->sql()->placeholder("SELECT ?field FROM ?t WHERE ?w", $field, $this->getTable(), $this->getWhereId($id))
									->fetchRow(0);
	}

	public function getFeildsId($fields, $id) {
		return $this->sql()->placeholder("SELECT ?fields FROM ?t WHERE ?w", $fields, $this->getTable(), $this->getWhereId($id))
									->fetchRow();
	}

	public function getDataWhere($where) {
		return $this->sql()->placeholder("SELECT * FROM ?t WHERE ?w", $this->getTable(), $where)
									->fetchAssoc();
	}

	public function getDataList($where) {
		return $this->sql()->placeholder("SELECT * FROM ?t WHERE ?w ORDER BY ?o", $this->getTable(), $where, $this->getSort())
									->fetchAssocAll('id');
	}



	// --------------- driver_db_list ---------------
	public function getNameList($filter=null, $fileds=null, $isName=true) {
		if(is_null($filter)) $filter = $this->getWhereFilter();
		if(is_array($fileds)) {
			if($isName) array_push($fileds, 'id', 'name');
			else array_push($fileds, 'id');
		} else {
			if($isName) $fileds = array('id', 'name');
			else $fileds = array('id');
		}

		return $this->sql()->placeholder("SELECT ?f FROM ?t WHERE ?w ORDER BY ?o", $fileds, $this->getTable(), $filter, $this->getSort())
									->fetchAssocAll('id');
	}


	public function getNameListGroup(array $where, $group) {
		return $this->sql()->placeholder("SELECT COUNT(*) AS count, ?field FROM ?t WHERE ?w GROUP BY ?field", $group, $this->getTable(), $where, $group)
									->fetchRowAll(1, 0);
	}

}

?>
