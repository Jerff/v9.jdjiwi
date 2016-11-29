<?php


abstract class driver_modul_list_tree extends driver_modul_list {

	private $colspan = null;

	// заполнение форм данными из базы
	public function runList($id=null, $offset=null, $limit=null) {
		$this->loadForm();

		list($data, $tree, $colspan) = $this->getDb()->runList($id);
		$this->setData($data);
		$this->setColspan($colspan);

		$listId = array();
		$this->runListTreeId($tree, $listId, $this->getDb()->filterStartView());
		return $listId;
	}

	protected function getParent() {
		return 0;
	}

	protected function runListTreeId(&$tree, &$listId, $parent=null) {
		if(is_null($parent)) $parent = $this->getParent();
		if(!isset($tree[$parent])) return;
		foreach($tree[$parent] as $id) {
			$listId[] = $id;
			if(isset($tree[$id])) $this->runListTreeId($tree, $listId, $id);
		}
	}

	protected function setColspan($colspan) {
		$this->colspan = $colspan;
	}
	public function getColspan() {
		return $this->colspan;
	}



	protected function saveEnd($send) {
		parent::saveEnd($send);

		if(isset($send['visible']) and !is_null($this->form()->get()->is('visible'))) {
			$this->getDb()->updateVisible($send);
		}
	}

}

?>