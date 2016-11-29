<?php


class basket_stat_db extends driver_db_list {

	public function returnParent() {
		return 'basket_edit_db';
	}

	protected function getTable() {
		return db_basket;
	}

	protected function getSort() {
		return array('id'=>'DESC');
	}

	public function loadData(&$row) {
		$row['registerDate'] = date("d.m.Y H:i", strtotime($row['registerDate']));
		$row['orderDate'] = date("d.m.Y H:i", strtotime($row['orderDate']));
		parent::loadData($row);
	}

	protected function getWhereFilter() {
		$filter = array();

		$status = $this->getFilter('status');
		if($status!=='all') {
			$filter[] = $this->sql()->getQuery("status IN(SELECT id FROM ?t WHERE stop=?)", db_basket_status, $status);
			$filter[] = 'AND';
		}

		$start = strtotime(cmfReformDate($this->getFilter('start'), '{d}-{m}-{Y}', '{Y}-{m}-{d}'));
        $end = strtotime(cmfReformDate($this->getFilter('end'), '{d}-{m}-{Y}', '{Y}-{m}-{d}'))+60*60*24;

		$filter[] = $this->sql()->getQuery("orderDate>=? AND orderDate<?", date('Y-m-d H:i:s', $start), date('Y-m-d H:i:s', $end));

		$filter[] = 'AND';
		$filter['delete'] = 'no';
		return $filter;
	}

}

?>