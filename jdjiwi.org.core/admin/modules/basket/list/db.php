<?php


class basket_list_db extends driver_db_list {

	public function returnParent() {
		return 'basket_edit_db';
	}

	protected function getTable() {
		return db_basket;
	}

	protected function getSort() {
		return array('registerDate'=>'DESC');
	}

	public function loadData(&$row) {
		$row['registerDate'] = date("d.m.Y H:i", strtotime($row['registerDate']));
		$row['changeDate'] = date("d.m.Y H:i", strtotime($row['changeDate']));
		parent::loadData($row);
	}

	protected function getWhereFilter() {
		$filter = array();

		$status = $this->getFilter('status');
		if($status!=='all') {
			$filter[] = $this->sql()->getQuery("status IN(SELECT id FROM ?t WHERE stop=?)", db_basket_status, $status);
			$filter[] = 'AND';
		}

		$filter['delete'] = 'no';
		return $filter;
	}

}

?>