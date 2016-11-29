<?php


class view_edit_action {


	static public function viewAction($res) {
		$i = 0;
		$content = '';
		foreach($res as $v) {
			if($i++) $content .= '<br />';
			$content .= $v['name'];
		}
		return $content;
	}

}

?>
