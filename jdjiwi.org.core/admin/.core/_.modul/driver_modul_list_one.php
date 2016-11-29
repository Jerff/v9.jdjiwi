<?php


abstract class driver_modul_list_one extends driver_modul_list {

	public function getCount(&$listId) {
		return $this->getDb()->getCount($listId);
	}

}

?>