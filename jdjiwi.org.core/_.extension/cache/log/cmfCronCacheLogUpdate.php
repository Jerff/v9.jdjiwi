<?php


class cmfCronCacheLogUpdate {

	static public function run() {
		cmfCronRun::run();
		$date = date('Y-m-d H:i:s');
		$res = cRegister::sql()->placeholder("SELECT `type`, `name`, `isSub` FROM ?t ORDER BY `date`", db_cache_update)
										->fetchAssocAll();
		$isClear = false;
		foreach($res as $row) {
			switch($row['type']) {
				case 'clear':
					if($isClear) {
						$isClear = true;
					}
					break;

				case 'tag':
					cmfCache::deleteTag($row['name']);
					break;

				case 'page':
					if(!$isClear) {
						cmfCronRun::run();
						cmfCachePages::clearPage($row['name']);
					}
					break;

				case 'url':
					if(!$isClear) {
						cmfCronRun::run();
						cmfCachePages::clearUrl($row['name'], (bool)$row['isSub']);
					}
					break;
			}
		}
		cmfCronRun::run();
		if($isClear) {
			cmfCacheSite::clear();
		}
		if($res) {
			cRegister::sql()->placeholder("DELETE FROM ?t WHERE date<=?", db_cache_update, $date);
		}
		cRegister::sql()->placeholder("DELETE FROM ?t WHERE time<=?", db_cache_data, time());
		cmfCronRun::free();
	}
}

?>