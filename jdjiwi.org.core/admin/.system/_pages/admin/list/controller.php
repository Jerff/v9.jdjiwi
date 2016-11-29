<?php


class _pages_admin_list_controller extends driver_controller_list_one {

	protected function init() {
		parent::init();
		$this->initModul('main',	'_pages_admin_list_modul');

		// url
		$this->url()->setSubmit('/admin/pages/admin/');
		$this->url()->setEdit('/admin/pages/admin/');

		$this->access()->writeAdd('updatePages|updateCache');
	}


	public function delete($id) {
		$id = cLoader::initModul('_pages_admin_edit_controller')->delete($id);
		return parent::delete($id);
	}

	public function copy($id) {
		$this->copyInit(true);
		cLoader::initModul('_pages_admin_edit_controller')->copy($id);
		$this->copyInit(false);
	}


	protected function updatePages() {
		cmfUpdatePages::start();
	}

	protected function updateCache() {
		cAdmin::cache()->deleteTag('access,menu,load');
	}


	public function getCount() {
		$listId = $this->getDataId();

		$type = cGlobal::get('pageType');
		$data = array();
		if(($type and $type!=='tree') or !count($listId)) return $data;

		$query = '';
		$sep = '';
		foreach($listId as $id) {
			$query .= $sep .
			$this->sql()->getQuery("(SELECT ?i AS id, count(id) AS count FROM ?t WHERE `type` IN('pages', 'pagesSystem') AND path LIKE '%[?i]%')", $id, db_pages_admin, $id);
			$sep = ' UNION ';
		}
		return $this->sql()->query($query)->fetchRowAll(0, 1);
	}

}

?>