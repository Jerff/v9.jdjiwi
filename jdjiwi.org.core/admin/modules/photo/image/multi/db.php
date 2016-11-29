<?php


class photo_image_multi_db extends driver_db_list {

    public function returnParent() {
		return 'photo_image_edit_db';
	}

	protected function getTable() {
		return db_photo_image;
	}

    public function runList($id=null, $offset=null, $limit=null) {
		return array();
	}

    protected function startSaveWhere() {
        return array('photo');
    }

	protected function getWhereId($list) {
		return array('id'=>$list, 'AND', 'photo'=> cAdmin::menu()->sub()->getId());
	}

}

?>