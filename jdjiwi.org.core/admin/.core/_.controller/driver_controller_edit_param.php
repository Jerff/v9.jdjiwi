<?php


abstract class driver_controller_edit_param extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->access()->writeAdd('paramAdd|paramDel|paramDelChecbox');
	}


	protected function paramAdd($param, $newId) {
		$new = cInput::post()->get($newId);
		if(empty($new)) return;
		$this->modul()->paramAdd($param, $new);
		$this->ajax()->script("cmf.setValue('{$newId}', '')");
	}

	protected function paramDel($param) {
		$name = $this->modul()->form()->get()->getId($param);
		$delete  = cInput::post()->get($name);
		$this->modul()->paramDel($param, $delete);
	}

	protected function paramDelChecbox($param) {
        $delete  = cInput::post()->get('delete'. $param);
        if($delete!=-1) {
    		$this->modul()->paramDel($param, $delete);
        }
	}

	public function paramView($isBasket=false) {
		view_param::paramView($this->html()->jsName(),
								$this->modul()->form()->get(),
								$this->modul()->getParam(),
								$isBasket);
	}

}

?>