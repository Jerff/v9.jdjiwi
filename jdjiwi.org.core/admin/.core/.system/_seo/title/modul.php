<?php


class _seo_title_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('_seo_title_db');

		// формы
		$form = $this->form()->get();
		$form->add('title',		new cmfFormTextarea());
		$form->add('keywords',	new cmfFormTextarea());
		$form->add('description',	new cmfFormTextarea());
		$form->add('value',		new cmfFormValue());
		$form->add('uri',			new cmfFormSelect());

		$element = $this->form()->get()->get('uri');
		cLoader::initModul('_pages_main_list_db')->getSelectTree($element);

		$id = $this->id()->get();
		if(!$id) {
			$id = current($element->getValueArray());
			$this->id()->set($id);
		}
        $element->select($id);
	}


	protected function selectForm($data) {
		parent::selectForm($data);
		$value = cLoader::initModul('_pages_main_list_db')->getPagesVariables($this->id()->get());
		if($value) {
			$this->form()->get()->select('value', $value);
		}
	}

	public function loadForm() {
		$value = cLoader::initModul('_pages_main_list_db')->getPagesVariables($this->id()->get());
		if($value) {
			$this->form()->get()->select('value', $value);
		}
		$this->form()->get()->select('uri', $this->id()->get());
	}


	public function save($data) {
		if(count($data)) {
			$this->getDb()->save($data);
			$this->updateForm();
			return true;
		}
		return false;
	}


	public function delete($listId=null) {
		$listId = parent::delete($listId);
		$this->updateForm();
		return $listId;
	}

	public function resetForm(){
		$this->form()->get()->reset();
		$this->form()->get()->select('uri', $this->id()->get());
	}

	protected function updateForm(){
		$element = $this->form()->get()->get('uri');
		cLoader::initModul('_pages_main_list_db')->getSelectTree($element);
	}

}

?>