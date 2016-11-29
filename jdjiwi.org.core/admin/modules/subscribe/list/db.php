<?php


class subscribe_list_db extends driver_db_list {

	public function returnParent() {
		return 'subscribe_edit_db';
	}

	protected function getTable() {
		return db_subscribe;
	}

	protected function getSort() {
		return array('date'=>'DESC');
	}

	public function loadData(&$row) {
		$row['date'] = date("d.m.Y H:i", strtotime($row['date']));
		parent::loadData($row);
	}

    protected function getWhereFilter() {
        $filter = array();

		if($type = $this->getFilter('type')) {
            if($type==='all') {
                $type = array_keys(model_subscribe::typeList());
                $type[] = 'all';
            } 
            $filter['type'] = $type;           
        } else {
            $filter[] = 1;
        }

		return $filter;
	}

}

?>