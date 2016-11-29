<?php


class _pages_admin_edit_modul extends driver_modul_edit_tree {

	protected function init() {
		parent::init();

		$this->setDb('_pages_admin_edit_db');

		// формы
		$form = $this->form()->get();
		$this->setNewPos();

		$form->add('parent',	new cmfFormSelectInt());
		$form->add('type',		new cmfFormSelect());

		$form->add('name',		new cFormText());
		$form->add('visible',	new cmfFormCheckbox());
		$form->add('isView',		new cmfFormCheckbox());

		if($id = $this->id()->get()) {
			list(, $type) = $this->getDb()->getType($id);
			cGlobal::set('pageType', $type);
		}
	}

	public function loadForm() {
		parent::loadForm();
		$parent = $this->getFilter('parent');
		if($this->id()->get()) {
			$data = $this->getData();
			$parent = $data['parent'];
			$type = get($data, 'type');
			$modulId = get($data, 'modul');

		} else {
			if($parent) {
				$type = $this->getDb()->getType($parent);
			} else {
				$type = 'tree';
				$smallName = '';
			}
			$modul = false;
		}
		cGlobal::set('pageType', $type);


		$form = $this->form()->get();



		if($type==='list') {
			$form->addElement('type', 'list', 			'Список страниц');
			$form->addElement('type', 'tree', 			'Узел');

			$form->add('modul',		new cmfFormSelect());
			foreach(cAccessModul::getListModulMenu() as $id=>$modul) {
				if(isset($modul['isPack'])) {
					$name = $modul['name'];
				} else {
					$name = '| ----- | '. $modul['name'];
					if(!$modulId) $modulId = $id;
				}

				$form->addElement('modul', $id, $name);
				if(isset($modul['isPack'])) {
					$form->addOptions('modul', $id, 'disabled', 'disabled');
					$form->addOptions('modul', $id, 'style', 'background-color: #000000; color: #FFFFFF; font-weight: bold');
				}
			}

			$form->add('modulMenu',	new cmfFormSelect());
			list($list, $modulName) = cAccessModul::getListMenu($modulId);
			$form->addElement('modulMenu', '', 'Не выбрано');
			foreach($list as $id=>$name) {
				$form->addElement('modulMenu', $id, $name);
			}

		} else {

			$form->addElement('type', 'tree', 			'Узел');
			$form->addElement('type', 'list', 			'Список страниц');
		}
	}

	public function onchangeModul() {
		$form = $this->form()->get();
		$form->add('modul',		new cmfFormSelect());
		$modulId = $form->handlerElement('modul');

		list($list, $modulName) = cAccessModul::getListMenu($modulId);
		$form->select('name', $modulName);
		$js = $form->get('name')->jsUpdate();

		$form->add('modulMenu',	new cmfFormSelect());
		$form->addElement('modulMenu', '', 'Не выбрано');
		foreach($list as $id=>$name) {
			$form->addElement('modulMenu', $id, $name);
		}
		$js .= $form->get('modulMenu')->jsUpdate();
		$this->ajax()->script($js);
	}



	protected function saveStart(&$send) {
		parent::saveStart($send);
		if(isset($send['type'])) {
			$this->command()->reloadView();
		}
	}

}

?>