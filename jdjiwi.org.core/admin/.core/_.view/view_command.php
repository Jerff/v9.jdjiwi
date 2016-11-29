<?php


class view_command {


	static public function viewLimitButton() {
		$arg = func_get_args();
		$td = array();
		if(func_num_args()>3) {
            for($i=func_num_args(); $i>3; $i--) {
            	$td[] = array_shift($arg);
            }
		}
		$limitUrl = get($arg, 0);
		$linkPage = get($arg, 1);
		$button = get($arg, 2);
		?>
			<table class="botton_and_radio_container_left">
			<tr>
			<? foreach($td as $id) { echo $id; } ?>
			<td class="view">Отображать по:</td>
			<td class="count"><?=cmfView::selectOncahge($limitUrl, 'size="1" class="spisok_novost_edit_right"') ?>
			</td>
			<? foreach($linkPage as $value) { ?>
				<td class="page"><?=$value ?></td>
			<? } ?>
			<td>&nbsp;</td>
			<td class="buttonList">
				<div class="botton_and_radio_container_right">
				<ul><?=$button ?></ul>
				</div>
			</td>
			</tr>
			</table><?
	}

	static public function viewLineButton() {
		if(!$command = func_get_args()) return;
		$button = array_pop($command); ?>

		<table class="botton_and_radio_container_left">
		<tr><?
		foreach($command as $k) {
			?><td><?=$k ?></td><?
		} ?>
		<td class="buttonList">
			<div class="botton_and_radio_container_right">
			<ul><?=$button ?></ul>
			</div>
		</td>
		</tr>
		</table><?
	}

	static public function viewLeftLineButton() {
		if(!$command = func_get_args()) return;
		$button = array_pop($command); ?>

		<table class="botton_and_radio_container_left">
		<tr>
		<td class="buttonList">
			<div class="botton_and_radio_container_left2">
			<ul><?=$button ?></ul>
			</div>
		</td>
		</tr>
		</table><?
	}

	static public function viewLineMenuButton($button1, $button2='') { ?>
		<table class="botton_and_radio_container_left">
		<tr>
		<td>
			<div class="botton_and_radio_container_left2">
			<ul><?=$button1 ?></ul>
			</div>
		</td>
		<td>
			<div class="botton_and_radio_container_right">
			<ul><?=$button2 ?></ul>
			</div>
		</td>
		</tr>
		</table><?
	}

}

?>
