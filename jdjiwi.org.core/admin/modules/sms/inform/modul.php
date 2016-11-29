<?php


class sms_inform_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('sms_inform_db');

		// формы
		$form = $this->form()->get();
		$this->setNewPos();
		$form->add('status',	    new cmfFormSelect(array('!empty')));
		$form->add('notice',		new cmfFormTextarea());
		$form->add('visible',	    new cmfFormCheckbox());
	}

    public function loadForm() {
		$form = $this->form()->get();
		$form->addElement('status', '',  'Выберите');
        $form->addElement('status', array('-----', 'newOrder'),  'Новый заказ');

        $name = cLoader::initModul('basket_status_list_db')->getNameList();
        foreach($name as $key=>$value)
            $form->addElement('status', array('Выставлен статус заказу', $key .'status'), $value['name']);
	}
}

?>