<?php


class basket_status_list_db extends driver_db_list {

	public function returnParent() {
		return 'basket_status_edit_db';
	}

	protected function startSaveWhere() {
		return array('stop');
	}

	protected function getSort() {
		return array('stop', 'pos');
	}

	public function loadData(&$row) {
		$pay = cConvert::pathToArray($row['pay']);
        $new = array();
        $data = array('no'=>'не оплачен',
                      'yes'=>'оплачен');
        foreach($pay as $v) if(isset($data[$v])){
            $new[] = $data[$v];
        }
        $row['pay'] = implode(', ', $new);
		parent::loadData($row);
	}

	protected function getTable() {
		return db_basket_status;
	}

	public function getStatusList($isPay=null) {
		$where = $isPay ? array($this->sql()->getQuery("pay='' OR pay LIKE '%[?s]%'", $isPay)) : array(1);
		return $this->sql()->placeholder("SELECT id, name, color, stop FROM ?t WHERE ?w ORDER BY stop, pos", $this->getTable(), $where)
								->fetchAssocAll('id');
	}


}

?>