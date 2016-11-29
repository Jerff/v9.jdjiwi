<?php


class article_edit_db extends driver_db_edit {

    public function updateController() {
		return 'model_article';
	}

	protected function getTable() {
		return db_article;
	}

}

?>