<?php


class cmfCacheSite {


	static public function clear() {
        cDir::clear(cCacheSitePath);
        cDir::clear(cCachePagePath);
	}

	static public function readMainPage($file) {
        if(file_exists($file)) {
			readfile($file);
			//echo file_get_contents($file);
			exit;
		}
	}

	static public function readPage($file) {
        if(file_exists($file)) {
			return file_get_contents($file);
		}
		return false;
	}

	static public function savePage($file, &$c) {
        $base = dirname($file);
        if(!is_dir($base)) {
        	if(!cDir::create($base)) return;
        }
        file_put_contents($file, $c);
	}

	static public function clearPage($main, $url=false) {
        if($url) {
            $url = str_replace(cAppUrl, '', $url);

        } else {
            cDir::clear(cCachePagePath . self::hash($main) .'/');
        }
	}

	static public function clearUrl($url, $isSub=true) {
        $url = substr(str_replace(cAppUrl, '', $url), 1);
        if($isSub) {
	        cDir::clear(cmfCache . $url, true);
        } else {
	        cDir::clear(cmfCache . $url .'5_qwerty/', true);
        }
	}


	// ���
	static private function hash($n) {
		return cHashing::crc32($n) . sha1($n);
	}

	// sub ����������
	static private function folder($n) {
		return substr($n, 0, 2) .'/'. substr($n, 2, 2);
	}

	static public function getFileMain() {
		$file =  cCacheSitePath . substr($_SERVER['REQUEST_URI'], 1) .'5_qwerty/_'. cmfCacheUser::getCacheId() .'.html';
		$file = str_replace(array('/../', '/./', '?'), array('', '', '=+=/=+='), $file);
		return $file;
	}

	static public function getFilePageOfMain($main, $page) {
		$file = self::hash($main . $page);
		return cCachePagePath . self::hash($main) .'/'. self::folder($page) .'/'. self::folder($file) .'/'. $file;
	}
	static public function getFilePage($page) {
		$file = self::hash($page);
		return cCachePagePath . self::hash($page) .'/'. self::folder($file) .'/'. $file;
	}


	static public function getFilePageUrlOfMain($main, $page, $url) {
		$file = self::hash($main . $page. $url);
		return cCachePagePath . self::hash($main) .'/'. self::hash($url) .'/'. self::folder($page) .'/'. self::folder($file) .'/'. $file;
	}
	static public function getFilePageUrl($page, $url) {
		$file = self::hash($page . $url);
		return cCachePagePath . self::hash($page) .'/'. self::hash($url) .'/'. self::folder($file) .'/'. $file;
	}

}

?>