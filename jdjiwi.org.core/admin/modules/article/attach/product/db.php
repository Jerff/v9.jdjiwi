<?php


class article_attach_product_db extends driver_db_list_product_attach {

	protected function getTable() {
		return db_article_attach;
	}

	protected function attach()  {
		return 'article';
	}

	protected function product()  {
		return 'product2';
	}


	public function save($send) {
		if(isset($send['visible'])) {
			$name = $this->attach();
			$product = $this->product();

			$id = $this->id()->get();
			$attach = (int)$this->getFilter($name);
			$value = $send['visible'];

			$table = $this->getTable();
			if($value==='yes') {
				$this->sql()->replace($table, array($name=>$attach, $product=>$id));
//				$this->getSql()->replace($table, array($name=>$id, $product=>$attach));
			}
			elseif($value==='no') {
				$this->sql()->del($table, array($name=>$attach, 'AND', $product=>$id));
//				$this->getSql()->del($table, array($name=>$id, 'AND', $product=>$attach));
			}
			$this->update()->set(array($product, $attach), $send);
		}
	}

	public function updateData($list, $send) {
        model_product::updateCache($list);
	}

}

?>