<?php


abstract class driver_db_edit_product_action extends driver_db_edit {

	protected $action = null;


	// переопределяемые функции
	abstract protected function getActionList();

	protected function action() {
		return 'action';
	}

	protected function product() {
		return 'product';
	}
	// /переопределяемые функции


	protected function setAction(&$action) {
		$this->action = $action;
	}

	public function getAction() {
		if(is_null($this->action)) {
			$action = $this->getActionList();
			$this->setAction($action);
		}
		return $this->action;
	}

	public function getActionKey() {
		$getAction = $this->getAction();
		if($getAction) return array_keys($getAction);
		else return array(0);
	}

	// --------------- filter для запросов списков к данных БД ---------------
	protected function getWhereList() {
		return array($this->action()=> $this->getActionKey());
	}

	protected function getWhereId($id) {
		return array($this->product()=>$id, 'AND', $this->action()=> $this->getActionKey());
	}

	public function save($send) {
		if($send) {
			$product = $this->product();
			$action = $this->action();

			$id = $this->id()->get();

			foreach($this->getActionKey() as $key) {
				$value = get($send, $key);
				if($value==='yes') {
					$this->sql()->replace($this->getTable(), array($product=>$id, $action=>$key));
					$this->updateSaveData($id, $key);
				} elseif($value==='no') {
					$this->sql()->del($this->getTable(), array($product=>$id, 'AND', $action=>$key));
					$this->updateDeleteData($id, $key);
				}
			}

			$this->update()->set($action);
		}
	}

	public function updateSaveData($id, $key) {
	}
	public function updateDeleteData($id, $key) {
	}

	/*public function saveId($id, $key) {
		$product = $this->product();
		$action = $this->action();

		$this->getSql()->replace($this->getTable(), array($product=>$id, $action=>$key));
	}*/


	// выборка данных записи из базы по фильтру
	public function runData() {
		$res = $this->sql()->placeholder("SELECT * FROM ?t WHERE ?w", $this->getTable(), $this->getWhere());
		$data = array();
		while($row = $res->fetchAssoc()) {
			$data[$row[$this->action()]] = 'yes';
		}
		$res->free();

		return $data;
	}


	public function delete(&$form, $id) {
		$product = $this->product();
		$this->sql()->del($this->getTable(), array($product=>$id));
	}



	public function deleteProduct($id) {
		$product = $this->product();
		$this->sql()->del($this->getTable(), array($product=>$id));
		$this->update()->set($id);
	}

	public function deleteAction($id) {
		$action = $this->action();
		$this->sql()->del($this->getTable(), array($action=>$id));
		$this->update()->set($id);
	}

}

?>
