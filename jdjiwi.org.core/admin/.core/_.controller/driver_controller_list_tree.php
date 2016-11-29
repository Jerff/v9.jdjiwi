<?php


abstract class driver_controller_list_tree extends driver_controller_list {


	protected function init() {
		parent::init();
		$this->setTotal(false);

//        $this->url()->setFunc();
		$this->access()->writeAdd('copy');
	}

	protected function getLimit() {
		return 1000;
	}


	public function getNewUrl($opt=null) {
		$opt['id'] = null;
		//$opt['parent'] = null;
		return $this->url()->getEdit($opt);
	}


	public function getAddChildUrl($opt=null){
		$opt['parent'] = $this->getKey();
		$opt['id'] = null;
		return $this->url()->getEdit($opt);
	}

    public function filterView() {
		$filter = array();
		$filter[1]['name'] = 'Не показывать подразделы';
		$filter[0]['name'] = 'Показывать подразделы';
		return parent::abstractFilter($filter, 'viewSubSection', 'end');
	}



	// заполнение форм данными из базы
	public function run($id=null) {

		$id = $this->modul()->runList($id);
		$this->setDataId($id);

		$modulAll = $this->modulAll();
		while(list($name, $modul) = each($modulAll)) {
			if($name!=='main') {
				$modul->runList($id);
			}
		}
	}


	public function getColspanLine($data) {
		return "colspan=". ($this->getKey() ? $data->colspan : $this->getColspan()) .'"';
	}
	public function getColspan() {
		return $this->modul()->getColspan();
	}



	protected function move1($id, $type=null) {
		if($this->modul()->getDb()->move1($id)) {
			$this->command()->reloadView();
		}
	}

	protected function move2($id, $type=null) {
		if($this->modul()->getDb()->move2($id)) {
			$this->command()->reloadView();
		}
	}

}

?>