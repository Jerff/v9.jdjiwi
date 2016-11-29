<?php


class _seo_title_db extends driver_db_edit {

	protected function getTable() {
		return db_seo_title;
	}

	public function save($data) {
		if(count($data)) {
			if(isset($data['uri'])) {
				$uri = $data['uri'];
				$this->id()->set($uri);
			}
			else $uri = $this->id()->get();
			unset($data['uri']);
			$this->sql()->add2($this->getTable(), $data, $this->getWhere());
			$this->update()->set($this->id()->get(), $data);
		}
	}


/*	public function delete() {
		$this->getSql()->del($this->getTable(), $this->getWhere());
	}
*/
	protected function getWhereId($id) {
		return array('uri'=>$id);
	}


	public function updateData($list, $send) {
		cmfUpdateCache::update('seoTitle');
	}

}

?>