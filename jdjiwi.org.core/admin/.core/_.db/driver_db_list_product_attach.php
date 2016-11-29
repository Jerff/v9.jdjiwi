<?php


abstract class driver_db_list_product_attach extends driver_db_list {

	private $attach = null;

	abstract protected function attach();

	protected function product() {
		return 'product';
	}

/*	// --------------- filter для запросов списков к данных БД ---------------
	protected function getWhereFilter() {
		$name = $this->attach();
		$product = $this->product();
		return array($name=> $this->getFilter($name), 'AND', $product=>$this->getFilter($product));
	}*/

	protected function getWhereId($list) {
		$name = $this->attach();
		return array($name=> $this->getFilter($name), 'AND', $this->product()=>$list);
	}

	public function save($send) {
		if(isset($send['visible'])) {
			$name = $this->attach();
			$product = $this->product();

			$id = $this->id()->get();
			$attach = (int)$this->getFilter($name);
			$value = $send['visible'];

			$table = $this->getTable();
			if($value==='yes') $this->sql()->replace($table, array($name=>$attach, $product=>$id));
			elseif($value==='no') $this->sql()->del($table, array($name=>$attach, 'AND', $product=>$id));

			$this->update()->set($id, $send);
		}
	}


	protected function loadSetDataList(&$data, &$row) {
		$data[$row[$this->product()]] = 'yes';
	}


	public function loadData(&$data) {
	}

	public function deleteProduct($id) {
		$product = $this->product();
		$this->sql()->del($this->getTable(), array($product=>$id));
	}

	public function deleteAttach($id) {
		$attach = $this->attach();
		$this->sql()->del($this->getTable(), array($attach=>$id));
	}

}

?>