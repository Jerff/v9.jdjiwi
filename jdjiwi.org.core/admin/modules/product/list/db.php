<?php


class product_list_db extends driver_db_list {

	public function returnParent() {
		return 'product_edit_db';
	}

	protected function getTable() {
		return db_product;
	}

	protected function startSaveWhere() {
		return array('section');
	}

	protected function getFields() {
		return array('id', 'section', 'brand', 'articul', 'name', 'pos', 'price', 'isOrder', 'count', 'count', 'visible');
	}

	protected function getSort() {
		$section = $this->sql()->placeholder("SELECT GROUP_CONCAT(id) FROM ?t ORDER BY path, pos", db_section)
		                            ->fetchRow(0);
		if($section) {
			return array('function'=>"FIELD(`section`, $section)", 'pos', 'name');
		} else {
		    return array('pos', 'name');
		}
	}


	protected function getWhereFilter() {
		$filter = array();

        $articul = $this->getFilter('articul');
        if($articul) {
            $filter[] = $this->sql()->getQuery("articul LIKE '%?s%'", $articul);
			return $filter;
        }

		$section = $this->getFilter('section');
		if($section>0) {
			$filter[] = $this->sql()->getQuery("section IN(SELECT id FROM ?t WHERE id=? OR path LIKE '%[?i]%')", db_section, $section, $section);
		} elseif(!$section) {
            $filter['section'] = cGlobal::get('$sectionId');
		} else {
            $filter[] = $this->sql()->getQuery("`section` NOT ?@", cGlobal::get('$sectionId'));
		}
		$filter[] = 'AND';
		$brand = $this->getFilter('brand');
		if($brand) {
            $filter['brand'] = $brand;
			$filter[] = 'AND';
		}


		$price1 = cConvert::toFloat($this->getFilter('price1'));
		$price2 = cConvert::toFloat($this->getFilter('price2'));
		if($price1 or $price2) {
			if($price1) $filter[] = "price >= '$price1' AND";
			if($price2) $filter[] = "price <= '$price2' AND";
		}

		switch($this->getFilter('filter')) {
            case 'dumpYes':
                $filter[] = "`count`>'0' AND";
                break;
            case 'dumpNo':
                $filter[] = "`count`='0' AND";
                break;

            case 'visibleYes':
                $filter['visible'] = 'yes';
			    $filter[] = 'AND';
                break;
            case 'visibleNo':
                $filter['visible'] = 'no';
			    $filter[] = 'AND';
                break;

            case 'new':
                $filter[] = "`created`>'". (time() - cSettings::read('catalog', 'novelty') *24*60*60) ."' AND";
                break;

            case 'sale':
                $filter['type'] = 'sale';
                $filter[] = 'AND';
                break;
		}

		$filter[] = 1;
		return $filter;
	}

	public function loadData(&$row) {
		$row['price'] = number_format($row['price'], 0, '.', ' ');
		//$row['dump'] = $row['dump']=='yes' ? 'да' : 'нет';
		parent::loadData($row);
	}

	public function getProductList($section, $filter=null, $fileds=null) {
		if(is_null($filter)) $filter = $this->getWhereFilter();
		if(is_array($fileds)) {
			array_push($fileds, 'id', 'name');
		} else {
			$fileds = array('id', 'name');
		}

		$res = $this->sql()->placeholder("SELECT ?fields:p FROM ?t p WHERE p.section ?@ AND ?w:p ORDER BY ?o:p", $fileds, $this->getTable(), $section, $filter, $this->getSort())
								->fetchAssocAll('id');
		return $res;
	}

}

?>