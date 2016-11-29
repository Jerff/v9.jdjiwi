<?php

cLoader::library('ajax/cControllerAjax');
class cControllerAjaxSave extends cControllerAjax {


	public function formSave() {
		cControllerAjaxView::formSave($this);
	}

	public function loadData() {
	}

	protected function processing() {
        $r = cRegister::request();

		$isError = $isUpdate = false;
		$data = array();
		foreach($this->form()->all() as $form) {
			$form->setRequest($r);
			$send = $form->processing();
			$isUpdate |= count($send);
			$data[] = $send;
		    $isError |= $form->isError();

		    $form->handler();
		}

		if(!$isError and $isUpdate) {
            return $this->form()->count()>1 ? $data : $data[0];
		}
		$this->runEnd(true);
	}

	protected function runError($error=null) {
		parent::runError($error);
		$this->runSave(false);
	}

	protected function runSaveOk() {
		$this->runSave(true);
		$this->runEnd();
	}

	protected function runSave($action) {
		static $i = false;
		if($i) return;
		$i = true;

		$idForm = $this->getIdForm();
		$idHash = $this->getIdHash();
		if($action) {
			$js = "cmf.style.show('#{$idForm}Save');";
		} else {
			$js = "cmf.style.hide('#{$idForm}Save');";
		}
		//$js .= "\n//document.location.hash = '$idHash';";
		cAjax::get()->script($js);
	}

}

?>