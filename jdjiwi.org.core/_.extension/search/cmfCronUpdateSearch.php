<?php


cLoader::library('cron/cmfCronUpdateDriver');
class cmfCronUpdateSearch extends cmfCronUpdateDriver {

	static private function reformStr(&$str) {
		$str = strip_tags($str);
		$str = str_replace(array('/'), ' ', $str);
		$str = preg_replace('~\s{2,}~', ' ', $str);
		$str = trim($str);
	}

	static public function init() {
		cmfCronRun::run();
        cmfCronCacheUpdate::start();
		cmfSearchData::update();
		cmfCronRun::free();
	}

}

?>