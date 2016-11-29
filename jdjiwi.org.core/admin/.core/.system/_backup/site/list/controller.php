<?php


class _backup_site_list_controller extends driver_controller_list_all {

	protected function init() {
		parent::init();
		$this->initModul('main',	'_backup_site_list_modul');

		// url
		$this->url()->setSubmit('/admin/backup/site/');
		$this->access()->writeAdd('newLine|resetDump');
	}


	public function delete($id) {
		parent::deleteList($id);
		return parent::delete($id);
	}

    protected function resetDump($id) {
        $this->id()->set($id);
        $this->modul()->getDb()->save(array('status'=>'none'));
        $this->ajax()->html('#status'. $id, 'Поставлен на обновление');
    }

}

?>