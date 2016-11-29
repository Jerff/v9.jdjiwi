<?php


abstract class driver_db_list extends driver_db_edit {


	// ORDER для запросов к данных БД ---------------
	protected function getSort() {
		return array('pos');
	}

	// where для запросов списков к данных БД ---------------
	protected function getWhereFilter() {
		return array(1);
	}


	// выборка данных записи из базы для стрницы
	public function runList($id=null, $offset=null, $limit=null) {
		return $this->runListData($this->runListQuery($id, $offset, $limit));
	}

	protected function runListQuery($id=null, $offset=null, $limit=null) {
		if(is_null($id)) {
			return $this->sql()->placeholder("SELECT SQL_CALC_FOUND_ROWS ?f FROM ?t WHERE ?w ORDER BY ?o LIMIT ?i, ?i ", $this->getFields(), $this->getTable(), $this->getWhereFilter(), $this->getSort(), $offset, $limit);
		}
		else {
			if(empty($id)) return false;
			return $this->sql()->placeholder("SELECT ?f FROM ?t WHERE ?w", $this->getFields(), $this->getTable(), $this->getWhereId($id)); // AND ?w, $this->filter()
		}
	}

	protected function &runListData($res) {
		$data = array();
		if($res) {
			while($row = $res->fetchAssoc()) {
				$this->loadData($row);
				$this->loadSetDataList($data, $row);
			}
		}
		return $data;
	}


	public function loadData(&$data) {
		if(isset($data['id'])) {
			$this->id()->set($data['id']);
			unset($data['id']);
		}
	}

	protected function loadSetDataList(&$data, &$row) {
		$data[$this->id()->get()] = $row;
	}



	public function getTotal() {
		return $this->sql()->getFoundRows();
	}



/*	public function updatePos($pos) {
		$this->getSql()->placeholder("UPDATE ?t SET pos=?", $this->getTable(), $pos);
	}*/

	public function move1($id) {
		return $this->move($id, true);
	}
	public function move2($id) {
		return $this->move($id, false);
	}
	private function move($id, $type) {
		if($type) {
			$sort1 = '<';
			$sort2 = 'DESC';
		} else {
			$sort1 = '>';
			$sort2 = 'ASC';
		}
		$this->id()->set($id);
		$where = array("a.`id`!='$id'");
		$join = $this->startSaveWhereJoin(array(), $where);

		$table = $this->getTable();

		$fields = $this->startSavePosField();
		if(!$row = $this->sql()->placeholder("SELECT `{$fields}` FROM ?t WHERE id=?", $table, $id)
							->fetchRow()) {
			return false;
		}
		list($pos1) = $row;

		if($join) {
			$row = $this->sql()->placeholder("SELECT a.id, a.`{$fields}` FROM ?t a, ?t b WHERE ?w AND a.`{$fields}`{$sort1}b.`{$fields}` ORDER BY a.`{$fields}` {$sort2} LIMIT 0, 1", $table, $table, $where)
							->fetchRow();
		} else {
			$row = $this->sql()->placeholder("SELECT a.id, a.`{$fields}` FROM ?t a, ?t b WHERE a.id!=b.id AND b.id=? AND a.`{$fields}`{$sort1}b.`{$fields}` ORDER BY a.`{$fields}` {$sort2} LIMIT 0, 1", $table, $table, $id)
							->fetchRow();
		}
		if(!$row) return false;
		list($id2, $pos2) = $row;

		$this->saveId($id2, array($fields=>$pos1));
		$this->saveId($id, array($fields=>$pos2));
		return array($id2, $pos1, $pos2);
	}

}

?>