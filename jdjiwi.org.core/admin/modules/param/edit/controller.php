<?php


class param_edit_controller extends driver_controller_edit {

	protected function init() {
		parent::init();
		$this->initModul('main',	'param_edit_modul');

		// url
		$this->url()->setSubmit('/admin/param/edit/');
		$this->url()->setCatalog('/admin/param/');

		$this->access()->writeAdd('paramUpdate|paramAdd|paramDelete');
	}

	public function filterMenu() {
		return parent::filterMenu2('Тип', 'group', 'param_group_edit_db');
	}

	public function delete($id) {
		parent::delete($id);
		//cmfModulLoad('product_param_db')->deleteParam($id);
		return $id;
	}

	protected function paramUpdate() {
		$this->modul()->getDb()->paramUpdate($this->id()->get(), cInput::post()->get('paramId'), cInput::post()->get('paramValue'));
		$this->paramView();
	}

	protected function paramAdd() {
		if($this->modul()->getDb()->paramAdd($this->id()->get(), cInput::post()->get('paramValue'))) {
	        $this->paramViewNew();
		}
		$this->paramView();
	}

	protected function paramDelete() {
		$this->modul()->getDb()->paramDelete($this->id()->get(), cInput::post()->get('paramId'));
		$this->paramView();
		$this->paramViewNew();
	}

	protected function paramViewNew() {
        $this->ajax()->script("
        	cmf.setValue('paramId', '');
        	cmf.setValue('paramValue', '');
        ");
	}
	protected function paramView() {
		$data = $this->modul()->getDb()->runData();
		if(!empty($data['value'])) {
            $data = unserialize($data['value']);
		} else {
			$data = array();
		}
		$param = array();
		foreach($data as $k=>$v) {
			$param[$k .' '. cString::specialchars($v)] = cString::specialchars($v);
		}

		ob_start(); ?>
<select size="25" class="width99" onchange="cmf.pages.select(this.value);">
<? foreach($param as $k=>$v) { ?>
   <option value="<?=$k ?>"><?=$v ?></option>
<? } ?>
</select><?

        $this->ajax()->html('#paramView', ob_get_clean());
        if(cGlobal::is('$paramLog')) {
            $this->ajax()->html('#paramLog', cGlobal::get('$paramLog'))
                                ->script("$('#paramLog').show();");
        } else {
            $this->ajax()->script("$('#paramLog').hide();");
        }
	}

}

?>