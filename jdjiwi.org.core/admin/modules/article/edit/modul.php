<?php


class article_edit_modul extends driver_modul_edit {

	protected function init() {
		parent::init();

		$this->setDb('article_edit_db');

		// формы
		$form = $this->form()->get();
		$form->add('section',		new cmfFormSelectInt(array('!empty')));
		$form->add('date',		new cmfFormTextDateTime());

		$form->add('uri',			new cFormText(array('uri'=>95)));
		$form->add('header',		new cmfFormTextarea(array('max'=>255, '!empty')));
		$form->add('image',	        new cmfFormFile(array('path'=>path_article)));
		$form->add('image_title',	new cFormText(array('max'=>255)));
		$form->add('notice',		new cmfFormTextarea(array('!empty')));
		$form->add('content',		new cmfFormTextareaWysiwyng('article', $this->id()->get()));
		$form->add('visible',		new cmfFormCheckbox());

		$form->add('title',			new cmfFormTextarea());
		$form->add('keywords',		new cmfFormTextarea());
		$form->add('description',		new cmfFormTextarea());
	}

    public function loadForm() {
        parent::loadForm();

        $form = $this->form()->get();
        $name = cLoader::initModul('catalog_section_list_tree')->getNameList(array('level'=>array(0, 1)));
        $form->addElement('section', 0, 'Отсуствует');
        foreach($name as $k=>$v) {
            $form->addElement('section', $k, $v['name']);
        }
        $form->select('section', $this->getFilter('section'));
	}

	protected function saveStart(&$send) {
		parent::saveStart($send);
		if(isset($send['notice']) and empty($send['notice'])) {
			$notice = $this->form()->get()->handlerElement('content');
			$send['notice'] = cString::subContent($notice);
		}

		parent::saveStartUri($send, 'header', 95);
	}

}

?>