<?php


class view_menu {


	static public function viewMenu($menu)  {
	    if(!$menu) return;
	?>
		<table class="great_table">
		<tr class="header_col">
		<td><?=$menu['header'] ?>: <a href="<?=$menu['url'] ?>"><?=$menu['name'] ?></a></td>
		</tr>
		</table><?
	}

	static public function viewMenu2($menu)  { ?>
		<br /><h2><?=$menu['header'] ?>: <a href="<?=$menu['url'] ?>"><?=$menu['name'] ?></a></h2></td>
		<br />
		<?
	}

}

?>
