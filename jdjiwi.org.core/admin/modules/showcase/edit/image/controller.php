<?php


class showcase_edit_image_controller extends driver_controller_list_all {

    function __construct($id=null) {
        $this->setIdName('image');
        parent::__construct($id);
	}

	protected function init() {
		parent::init();
		$this->initModul('main',	'showcase_edit_image_modul');

		// url
		$this->url()->setSubmit('/admin/showcase/edit/');
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
		$filter[0]['name'] = 'Выберите раздел';
		$filter[0]['parent'] = 1;
		$filter = parent::abstractFilter($filter, 'section', 'end');
  		$res = cRegister::sql()->placeholder("SELECT section, count(id) FROM ?t WHERE section ?@ GROUP BY section", db_baner, array_keys($filter))
									->fetchRowAll(0, 1);
		foreach($filter as $k=>$v) {
        	$c = get($res, $k, 0);
        	$filter[$k]['name'] = '['. ($c>9 ? $c : '0'. $c) .'] '. $v['name'];
		}
		return $filter;
	}

}

?>