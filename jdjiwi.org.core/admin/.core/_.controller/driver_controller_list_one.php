<?php


class driver_controller_list_one extends driver_controller_list {


	function __construct($limitAll=null, $limitPage=null) {
		$this->setIdName('list');
		parent::__construct($limitAll, $limitPage);

        $this->url()->setSubmit('/admin/content/pages/edit/');
		$this->access()->writeAdd('copy');
	}


	protected function getLimit() {
		return 1000;
	}


	public function getAddChildUrl($opt=null){
		$opt['add'] = 1;
		return $this->url()->getNew($opt);
	}


	public function getCount() {
		$listId = $this->getDataId();
		return $this->modul()->getCount($listId);
	}

	public function getEditUrl($opt=null) {
		$opt['id'] = $this->id()->get();
		return parent::getEditUrl($opt);
	}

}

?>