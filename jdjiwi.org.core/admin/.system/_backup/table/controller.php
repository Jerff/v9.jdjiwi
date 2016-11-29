<?php


class _backup_table_controller extends driver_controller_edit {

	protected function init() {
		parent::init();

		// url
		$this->url()->setSubmit('/admin/backup/table/');

		$this->access()->writeAdd('optimize|exportDumpPages|importDumpPages|exportDump|importDump');
	}

	protected function optimize() {
		cmfBackupTable::optimize();
	}

	public function exportDumpPages() {
        cmfBackupTable::exportDumpPages();
 	}

	protected function importDumpPages() {
		cmfBackupTable::importDumpPages();
		$this->ajax()->alert('Восстановление завершено');
	}

	protected function exportDump() {
		cmfBackupTable::exportDump();
	}

	protected function importDump() {
		cmfBackupTable::importDump();
		$this->ajax()->alert('Восстановление завершено');
	}

}

?>