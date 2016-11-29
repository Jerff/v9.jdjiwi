<?php


class _seo_sitemap_list_modul extends driver_modul_list {

	protected function init() {
		parent::init();

		$this->setDb('_seo_sitemap_list_db');

		// формы
		$form = $this->form()->get();
		$this->setNewPos();
		$form->add('name',		new cmfFormSelect());
		$form->add('changefreq',	new cmfFormSelect());
		$form->add('priority',	new cmfFormTextFloat(array('min'=>0, 'max'=>1)));
		$form->add('visible',		new cmfFormCheckbox());
	}

	public function loadForm() {
		$form = $this->form()->get();
        foreach(cmfSiteMapConfig::menu() as $k=>$v) {
			$form->addElement('name', $k, $v);
        }

        $form->addElement('changefreq', 'always', 'всегда');
        $form->addElement('changefreq', 'hourly', 'каждый час');
        $form->addElement('changefreq', 'daily', 'ежедневно');
        $form->addElement('changefreq', 'weekly', 'еженедельно');
        $form->addElement('changefreq', 'monthly', 'ежемесячно');
        $form->addElement('changefreq', 'yearly', 'ежегодно');
        $form->addElement('changefreq', 'never', 'никогда');
        $form->select('changefreq', 'weekly');

        $form->select('priority', '0.5');
	}

	public function newLine($index, &$data) {
		$data['changefreq'] = 'weekly';
		$data['priority'] = '0.5';
		return parent::newLine($index, $data);
	}

}

?>