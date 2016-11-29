<?php


abstract class cmfCacheDriver {

	// ошибка запуска драйвера
	private $isError = false;
	protected function setError() {
		$this->isError = true;
	}

	public function isRun() {
		return !$this->isError;
	}


	// кеш данных по имени
	abstract public function set($n, $v, $tags, $time);
	abstract public function get($n);
	abstract public function delete($n);
	abstract public function deleteTime();
	abstract public function deleteTag($tags);
	abstract public function clear();

}

?>
