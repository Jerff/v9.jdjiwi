<?php


class _backup_site_import_controller extends driver_controller_edit {

	protected function init() {
		parent::init();

		// url
		$this->url()->setSubmit('/admin/backup/site/');
		$this->access()->writeAdd('importBackup');
		$this->access()->readAdd('selectBackup');
	}

	public function getFileList() {
	    return cmfBackupSite::getFileList();
	}

	protected function selectBackup() {
	    $res = cmfBackupSite::getFileList();
	    $file = cInput::post()->get('backup');
	    if(!isset($res[$file])) {
	        return;
	    }
	    $res = $res[$file];

	    $content = $res['name'] .'<div class="clearFloat"></div>';
	    foreach($res['modul'] as $k=>$v) {
	        $content .= '<label><input name="select['. $k .']" type="checkbox">&nbsp;'. $v .'</label>';
	    }
	    $content .= '<div class="clearFloat"></div>'.
	                cmfAdminView::onclickType1("if(confirm('Загрузить дамп?')) edit.postAjax('importBackup');", 'Загрузить дамп');
	    $this->ajax()->html('selectDump', $content);
	}

	protected function importBackup() {
	    $res = cmfBackupSite::getFileList();
	    $file = cInput::post()->get('backup');
	    if(!isset($res[$file])) {
	        return;
	    }
	    $res = $res[$file]['modul'];
	    $select = cInput::post()->get('select');
	    foreach($res as $k=>$v) {
	        if(!isset($select[$k])) {
	            unset($res[$k]);
	        }
	    }
	    if(!$res) {
	        return;
	    }
	    cmfBackupSite::import($file, $res);
        cmfCronUpdateSearch::init();
        cmfCronCacheUpdate::start();
	    $this->ajax()->alert('Восстановление завершено');
	}

}

?>