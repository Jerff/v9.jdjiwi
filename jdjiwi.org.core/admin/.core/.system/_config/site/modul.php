<?php


class _config_site_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('_config_site_db');

		// формы
		$form = $this->form()->get();
		$form->add('newsLimit',	    new cmfFormSelectInt());
		$form->add('newsPage',	    new cmfFormSelectInt());

		$form->add('productLimit',	new cmfFormSelect());
		$form->add('priceStep',	    new cmfFormSelect());

		$form->add('showcaseMain',	new cmfFormSelectInt());
		$form->add('showcaseSmall',	new cmfFormSelectInt());
		$form->add('showcaseCatalog',	new cmfFormSelectInt());

		$form->add('banerMain',	    new cmfFormSelectInt());
		$form->add('banerCatalog',	new cmfFormSelectInt());


		$form->add('userLimit',	new cmfFormSelect());
		$form->add('orderLimit',	new cmfFormSelect());
		$form->add('orderPage',	new cmfFormSelect());
	}

	public function loadForm() {
		$form = $this->form()->get();

 		foreach(array(5, 10, 15, 20) as $id) {
			$form->addElement('newsLimit', $id, $id);

			$form->addElement('userLimit', $id, $id);
			$form->addElement('orderLimit', $id, $id);
		}

 		for($id=5; $id<=10; $id++) {
			$form->addElement('newsPage', $id, $id);
			$form->addElement('orderPage', $id, $id);
		}

 		foreach(array('9-12-24', '3-6-9') as $id) {
			$form->addElement('productLimit', $id, $id);
		}

 		for($id=2; $id<=10; $id++) {
 		    $form->addElement('priceStep', $id, $id);
 		}

 		for($id=2; $id<=60; $id++) {
			$form->addElement('showcaseMain', $id, $id);
			$form->addElement('showcaseSmall', $id, $id);
			$form->addElement('showcaseCatalog', $id, $id);

			$form->addElement('banerMain', $id, $id);
			$form->addElement('banerCatalog', $id, $id);
		}
	}

}

?>