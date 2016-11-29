<?php


class cmfCronCacheUpdate {


    static public function start() {
        self::updateData();
        self::updateCache();
    }

	static public function updateData() {
		cmfCronRun::run();
		model_product::isProduct();
		model_product::updateSearchId();
		cmfContentUrl::update();
	}

    static private function updateCache() {
		cmfCronRun::run();
        cRegister::sql()->truncate(db_cache_update);
        cmfCache::clear();
        cmfCacheSite::clear();
    }

}

?>