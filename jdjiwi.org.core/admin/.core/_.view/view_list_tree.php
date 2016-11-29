<?php


class view_list_tree {


	static public function viewTreeLine($key, $data) {
		if($key and $data->level) echo '<td colspan="'. $data->level .'">&nbsp;</td>';
	}

}

?>
