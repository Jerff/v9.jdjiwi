<?php


class product_image_multi_db extends driver_db_list {

    public function returnParent() {
		return 'product_image_edit_db';
	}

	protected function getTable() {
		return db_product_image;
	}

    public function runList($id=null, $offset=null, $limit=null) {
		return array();
	}

    protected function startSaveWhere() {
        return array('product');
    }

	protected function getWhereId($list) {
		return array('id'=>$list, 'AND', 'product'=> cAdmin::menu()->sub()->getId());
	}

}

?>