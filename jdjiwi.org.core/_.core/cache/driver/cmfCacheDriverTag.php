<?php

cLoader::library('cache/driver/cmfCacheDriver');
abstract class cmfCacheDriverTag extends cmfCacheDriver {

	const tagId = 'cmfCacheDriverTag';


	abstract protected function setId($n, $v, $time);
	abstract protected function getId($n);
	abstract protected function deleteId($n);

    //функция хеширования
	protected function hash($n) {
		return cHashing::crc32($n) . sha1($n);
	}


	// хранение тегов
	private function getTag() {
		return $this->getId(self::tagId);
	}
	private function setTag($tag) {
		return $this->setId(self::tagId, $tag, 0);
	}


	public function get($n) {
		return $this->getId($n);
	}

	public function set($n, $v, $tags, $time) {
		$n = $this->hash($n);
		if($tags) {
			$save = $this->getTag();
			$tags = is_string($tags) ? explode(',', $tags) : $tags;
			foreach($tags as $tag) {
				$save[$tag][$n] = $n;
			}
			$this->setTag($save);
		}
		$this->setId($n, $v, $time);
	}

	public function delete($n) {
		$n = $this->hash($n);
		$save = $this->getTag();
		foreach($save as $k) {
			if(isset($k[$n])) {
				unset($k[$n]);
			}
		}
		$this->setTag($save);
		$this->deleteId($n);
	}

	public function deleteTag($tags) {
		if(!$tags) return;
		$save = $this->getTag();
		foreach(explode(',', $tags) as $tag) {
			if(isset($save[$tag])) {
				foreach($save[$tag] as $k) {
					$this->deleteId($k);
				}
				unset($save[$tag]);
			}
		}
		$this->setTag($save);
	}

	public function deleteTime() {
		$save = $this->getTag();
		foreach($save as $tag=>&$list) {
			foreach($list as $n) {
				if(!$this->getId($n)) {
					unset($list[$n]);
				}
			}
			if(!$save[$tag]) {
				unset($save[$tag]);
			}
		}
		$this->setTag($save);
	}

}

?>