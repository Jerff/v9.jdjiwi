<?php

cLoader::library('view/cmfView');
class cmfAdminView {

    static private function view($name) {
        if(!(trim($name))) {
            return '__';
        } else {
            return $name;
        }
    }

	// шаблон пути
	static public function path($path, $separator='&nbsp;/&nbsp;') {
		if(!is_array($path)) return null;
		$str = $sep = '';
		foreach($path as $value) {
			$str .= $sep . self::url(isset($value['url']) ? self::openUrl($value['url']) : '', $value['name']) ;
			$sep = $separator;
		}
		return $str;
	}


	static function attach($key, $list) {
		if(is_null($key)) return;
		$i = 0;
		foreach($key as $key2)
			if(isset($list[$key2])) echo ($i++ ? '<br>': ''). $list[$key2];
	}


	static public function openUrl($url) {
		return 'javascript:cmf.redirect(\''. $url .'\');';
	}
	// шаблон ссылок
	static public function url($url, $name) {
		return '<span class="button"><a '. ($url ? 'href="'. $url .'"': '').'>'. self::view($name) .'</a></span>';
	}
	static public function url2($url, $name) {
		return self::url2('<b>'. self::view($name) .'</b>');
	}


	// шаблон
	static function buttonType1($onclick, $name) {
		$id = preg_replace('~(.*?([a-z]*)\.([a-z]*)[^a-z]*([a-z]*).*)~is', '$2_$3_$4', $onclick) ;
		return '<li class="item_botton" id="'. $id .'"><a onclick="'. $onclick .'"><span class="botton_and_radio_container_right_text_botton">'. self::view($name) .'</span></a></li>';
	}
	static function buttonType2($url, $name) {
		return '<li class="item_botton"><a href="'. $url .'"><span class="botton_and_radio_container_right_text_botton">'. self::view($name) .'</span></a></li>';
	}
	static function buttonType3($url, $name) {//cAdminUrl
		return '<span class="button"><a href="'. $url .'">'. self::view($name) .'</a></span>';
	}
	static function buttonType4($url, $name) {//cAdminUrl
		return '<span class="button"><a href="'. $url .'" target="_blank">'. self::view($name) .'</a></span>';
	}



	static function onclickType1($onclick, $name) {//cmfAdminOnclickA
		return '<span class="button"><a class="button" onclick="'. $onclick .'">'. self::view($name) .'</a></span>';
	}
	static function onclickType2($onclick, $name) {//cmfAdminOnclick_ib
		return '<span onclick="'. $onclick .'" class="button link"><i><b>'. self::view($name) .'</b></i></span>';
	}
	static function onclickType3($onclick, $name) {//cmfAdminOnclick_b
		return '<div class="bt" onclick="'. $onclick .'"><i><b><a title="' .$name. '">'. self::view($name) .'</a></b></i></div>';
	}
	static function onclickType4($onclick, $name) {//cmfAdminOnclick_bl
		return '<div class="bt_l bt" onclick="'. $onclick .'"><i><b><a title="' .$name. '">'. self::view($name) .'</a></b></i></div>';
	}



	static function pagination($url, $page, &$page_link, $name='page') {
		foreach($page_link as $key=>$value)
			if($key==$page) $page_link[$key] = '<span class="linkPage">'. $value .'</span>';
			else $page_link[$key] = '<a href="'. $url .'&'. $name .'='. $key. '">'. $value .'</a>';
	}

}

?>