<?php


class cmfView {

	static public function date($time) {
		$res = array('января', 'февраля', 'марта', 'апрель', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
		return date('j', $time) .' '.  $res[date('m', $time)-1] .' '. date('Y', $time);
	}


	static public function selectOncahge($select, $style='class="width50"') {
		$result='
<select onchange="document.location.href = this.value;" '. $style .'>';
		reset($select);
		while(list(, $v) = each($select)) {
			$result .= '
<option value="'.$v['url'].'" '.((isset($v['sel']))?'selected':'').'>'.$v['name'].'</option>';
		}
		$result .= '
</select>';
		return $result;
	}


	static public function select($select, $style='class="width50"') {
		$result='
<select '. $style .'>';

		reset($select);
		while(list($k, $v) = each($select))
			$result .= '
<option value="'.$k.'" '.((isset($v['sel']))?'selected':'').'>'.$v['name'].'</option>';
		$result .= '
</select>';
		return $result;
	}


    static function optionView($opt) {
        foreach($opt as $k=>$v) {
            ?><option value="<?=$v['url'] ?>" <?= isset($v['sel']) ? 'selected' : '' ?>><?=$v['name'] ?></option><?
        }
    }

}

?>