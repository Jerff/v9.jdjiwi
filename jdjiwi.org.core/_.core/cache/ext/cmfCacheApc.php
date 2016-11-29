<?php

cLoader::library('cache/driver/cmfCacheDriverTag');
class cmfCacheApc extends cmfCacheDriverTag {

	function __construct() {
		if (!extension_loaded('apc')) {
            $this->setError();
		}
	}

	protected function setId($n, $v, $time) {
		apc_store($n, $v, $time);
	}

	protected function getId($n) {
		return apc_fetch($n)
	}

	protected function deleteId($n) {
		apc_delete($n)
	}

	public function clear() {
		apc_clear_cache('user')
	}

}

?>