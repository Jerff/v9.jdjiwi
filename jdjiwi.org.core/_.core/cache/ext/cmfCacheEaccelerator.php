<?php

cLoader::library('cache/driver/cmfCacheDriverTag');
class cmfCacheEaccelerator extends cmfCacheDriverTag {

	function __construct() {
		if (!extension_loaded('eaccelerator')) {
            $this->setError();
		}
	}

	protected function setId($n, $v, $time) {
		eaccelerator_put($n, $v, $time);
	}

	protected function getId($n) {
		if(xcache_isset($n)) {
            return eaccelerator_get($n);
		} else {
			return false;
		}
	}

	protected function deleteId($n) {
        eaccelerator_rm($n);
	}

	public function clear() {
		eaccelerator_clean();
	}

	public function deleteTime() {
		parent::deleteTime();
		eaccelerator_gc();
	}

}

?>