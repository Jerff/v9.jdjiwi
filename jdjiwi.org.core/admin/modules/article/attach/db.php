<?php


class article_attach_db extends product_list_db {

	public function loadData(&$row) {
//		$row['dump'] = get($row, 'dump')==='yes' ? 'есть' : 'нет';
		$row['visible'] = $row['visible']==='yes' ? 'есть' : 'нет';
		parent::loadData($row);
	}


	protected function runListQuery($id=null, $offset=null, $limit=null) {
		$attach = $this->getFilter('attach');
		$action = $this->getFilter('article');
		if(!is_null($id) or $attach==='all') {
			return parent::runListQuery($id, $offset, $limit);
		}

		return $this->sql()->placeholder("SELECT SQL_CALC_FOUND_ROWS ?f FROM ?t WHERE id IN(SELECT product2 FROM ?t WHERE article=?) AND ?w ORDER BY ?o LIMIT ?i, ?i ", $this->getFields(), $this->getTable(), db_article_attach, $action, $this->getWhereFilter(), $this->getSort(), $offset, $limit);
	}

}

?>