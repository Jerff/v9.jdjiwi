<?php


abstract class driver_db_gallery_list extends driver_db_list {


	public function loadData(&$row) {
		if(cString::strlen($row['name'])>12) {
		    $row['header'] = cString::substr($row['name'], 0, 12) .'...';
		} elseif(empty($row['name'])) {
		    $row['header'] = 'редактировать';
		} else {
		    $row['header'] = $row['name'];
		}
		parent::loadData($row);
	}

}

?>