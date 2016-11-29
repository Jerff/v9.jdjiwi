<?php


class baner_catalog_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->initModul('main',	'baner_catalog_modul');

		// url
		$this->url()->setSubmit('/admin/baner/');
		$this->access()->readAdd('onchangeSection|onchangeProduct');
	}

    public function loadForm2() {
        return $this->modul()->loadForm2($this->id()->get());
	}

	protected function onchangeSection($id) {
		$this->modul()->onchangeSection($id);
	}

	protected function onchangeProduct($id) {
		$this->modul()->onchangeProduct($id);
	}

	public function delete($id) {
		parent::delete($id);
		$this->ajax()->redirect($this->url()->getSubmit());
	}

	public function filterSection() {
		$filter = cLoader::initModul('catalog_section_list_tree')->getNameList();
		$filter[0]['name'] = 'Раздел не выбран';
		$filter[0]['parent'] = 0;
		$filter = parent::abstractFilter($filter, 'section', 'end');
  		$res = cRegister::sql()->placeholder("SELECT parent, count(id) FROM ?t WHERE parent ?@ AND parentBrand='0' GROUP BY parent", db_baner, array_keys($filter))
									->fetchRowAll(0, 1);
		foreach($filter as $k=>$v) {
        	$c = get($res, $k, 0);
        	$filter[$k]['name'] = '['. ($c>9 ? $c : '0'. $c) .'] '. $v['name'];
		}
		return $filter;
	}

	public function filterBrand() {
		$filter = cLoader::initModul('catalog_brand_list_db')->getNameList();
		$filter[0]['name'] = 'Производитель не выбран';
		$filter[0]['parent'] = 0;
		$filter = parent::abstractFilter($filter, 'brand', 'end');
  		$res = cRegister::sql()->placeholder("SELECT parentBrand, count(id) FROM ?t WHERE parent='0' AND parentBrand ?@ GROUP BY parentBrand", db_baner, array_keys($filter))
									->fetchRowAll(0, 1);
		foreach($filter as $k=>$v) {
        	$c = get($res, $k, 0);
        	$filter[$k]['name'] = '['. ($c>9 ? $c : '0'. $c) .'] '. $v['name'];
		}
		return $filter;
	}

}

?>