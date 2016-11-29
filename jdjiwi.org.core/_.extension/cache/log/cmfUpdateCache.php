<?php


class cmfUpdateCache extends cmfUpdateCacheLogDrive {

    static public function start() {
        cRegister::sql()->truncate(db_cache_update);
        cmfCache::clear();
        cmfCacheSite::clear();
    }

    public function showcase($id) {
    }

    public function contact() {
        $this->deleteTags('contact');
    }

}

?>
