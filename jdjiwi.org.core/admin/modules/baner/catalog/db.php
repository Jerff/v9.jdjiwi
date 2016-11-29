<?php


class baner_catalog_db extends driver_db_list {

    protected function init() {
        $this->setModel('model_baner');
        $this->setTable(db_baner);
        $this->command()->replace()->newRecord()->reload();
    }

	protected function startSaveWhere() {
		return array('parent');
	}

	protected function getWhereFilter() {
		$filter = array();
		$filter['parent'] = (int)$this->getFilter('section');
		$filter[] = 'AND';
		$filter['parentBrand'] = (int)$this->getFilter('brand');
		return $filter;
	}

}

?>