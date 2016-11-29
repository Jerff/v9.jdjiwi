<?php


class product_image_edit_db extends driver_db_edit {

    public function updateController() {
		return 'model_product_image';
	}

	protected function getTable() {
		return db_product_image;
	}

    protected function startSaveWhere() {
        return array('product');
    }

	protected function getWhereId($list) {
		return array('id'=>$list, 'AND', 'product'=> cAdmin::menu()->sub()->getId());
	}

	public function getUpdateParentId() {
		return cAdmin::menu()->sub()->getId();
	}
	protected function getDeleteModelId($list_id) {
		$list_id = (array)cAdmin::menu()->sub()->getId();
		return array_combine($list_id, $list_id);
    }

}

?>