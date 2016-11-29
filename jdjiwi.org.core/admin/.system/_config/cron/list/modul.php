<?php


class _config_cron_list_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('_config_cron_list_db');

		// формы
		$form = $this->form()->get();
		$this->setNewPos();
		$form->add('name',		    new cmfFormSelect());
		$form->add('changefreq',	new cmfFormSelect());
		$form->add('visible',		new cmfFormCheckbox());
	}

	public function loadForm() {
		$form = $this->form()->get();
        foreach(cmfCronConfig::menu() as $k=>$v) {
			$form->addElement('name', $k, $v);
        }

		$form->addElement('changefreq', 'minutes 30', 'раз в полчаса');
        $form->addElement('changefreq', 'hourly', 'каждый час');
        $form->addElement('changefreq', 'hourly 3', 'каждые 3 часа');
        $form->addElement('changefreq', 'hourly 6', 'каждые 6 часов');
        $form->addElement('changefreq', 'daily', 'ежедневно');
        $form->addElement('changefreq', 'daily 6', 'ежедневно после 6 часов');
        $form->addElement('changefreq', 'daily 12', 'ежедневно после 12 часов');
        $form->addElement('changefreq', 'daily 18', 'ежедневно после 18 часов');
        $form->addElement('changefreq', 'weekly', 'еженедельно');

        $form->select('changefreq', '3');
	}

	public function newLine($index, &$data) {
		$data['changefreq'] = '3';
		return parent::newLine($index, $data);
	}

}

?>