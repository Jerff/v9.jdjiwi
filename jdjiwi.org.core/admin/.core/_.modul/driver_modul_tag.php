<?php


abstract class driver_modul_tag extends driver_modul_edit {

	protected function init() {
		parent::init();

		$form = $this->form()->get();
		$form->add('tag',		new cmfFormTextarea(array('max'=>1000)));
	}

	public function getTagsListView() {
		return '<div id="tagList" class="tagList">'. $this->getTagsList() .'</div>';
	}

	public function getTagsList() {
		$product = $this->getDb()->attach();
		$res = $this->sql()->placeholder("SELECT id, name, $product FROM ?t ORDER BY name", db_tag)
								->fetchAssocAll('id');
		$max = 1;
		foreach($res as $row) {
			if($row[$product]>$max) {
				$max = $row[$product];
			}
		}

		$id = $this->form()->get()->getName('tag');
		$content = '';
		foreach($res as $row) {
			$h1 = 6-(int)($row[$product]/$max*5);
            $content .= <<<HTML
<span class="h{$h1}" onclick="tagCahangeOnclick('$id', this)">{$row['name']}</span>&nbsp;
HTML;
		}
		return $content;
	}

	public function updateOk() {
		parent::updateOk();
		$this->ajax()->html('tagList', $this->getTagsList());
	}

}

?>
