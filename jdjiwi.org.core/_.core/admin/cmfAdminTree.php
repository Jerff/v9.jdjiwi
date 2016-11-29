<?php


class cmfAdminTree {

 	public static function updateVisible($table, $parent=0) {
		$sql = cRegister::sql();
        if($parent) {
            $_no = $sql->placeholder("SELECT id FROM ?t WHERE visible='no' AND (id=? OR path LIKE '%[?i]%')", $table, $parent, $parent)
                        ->fetchRowAll(0, 0);
        } else {
            $_no = $sql->placeholder("SELECT id FROM ?t WHERE visible='no'", $table)
                        ->fetchRowAll(0, 0);
        }

        if($parent) {
            $res = $sql->placeholder("SELECT id, path FROM ?t WHERE path LIKE '%[?i]%' ORDER BY path", $table, $parent)
                        ->fetchAssocAll();
        } else {
            $res = $sql->placeholder("SELECT id, path FROM ?t WHERE (path IS NOT NULL OR path!='') ORDER BY path", $table)
                        ->fetchAssocAll();
        }
        foreach($res as $row) {
            $path = explode('][', substr($row['path'], 1, -1));
            while(list(, $v) = each($path)) {
                if(isset($_no[$v])) {
                    $_no[$row['id']] = $row['id'];
                    break;
                }
            }
        }

        if($parent) {
            $sql->placeholder("UPDATE ?t SET isVisible='yes' WHERE (id=? OR path LIKE '%[?i]%')", $table, $parent, $parent);
        } else {
            $sql->placeholder("UPDATE ?t SET isVisible='yes'", $table);
		}
		$sql->placeholder("UPDATE ?t SET isVisible='no' WHERE id ?@", $table, $_no);
	}

 	public static function updateUri($table, $id) {
		$sql = cRegister::sql();
		$res = $sql->placeholder("SELECT path, uri FROM ?t WHERE id=?", $table, $id);
		if(!$res) {
			$uri = '';
		} else {
			list($path, $uri) = $res->fetchRow();

			if($path) {
				$path = explode('][', substr($path, 1, -1));
				$path = $sql->placeholder("SELECT id, uri FROM ?t WHERE id ?@", $table, $path)
								->fetchRowAll(0, 1);
				$uri = implode('/', $path) .'/'. $uri;
			}
		}

		$sql->placeholder("UPDATE ?t SET isUri=? WHERE id=?", $table, $uri, $id);


		$res = $sql->placeholder("SELECT id, parent, uri FROM ?t WHERE path LIKE '%[?i]%'", $table, $id);
		$_child = array();
		while($row = $res->fetchAssoc()) {
			$_child[$row['parent']][$row['id']] = $row['uri'];
		}
		$res->free();

		$result = array();
		self::updateUriChild($result, $table, $uri, $id, $_child);

		return array($uri, $result);
	}

	public static function updateUriChild(&$result, $table, $uri, $parent, $_child) {
		if(!isset($_child[$parent])) return;

		foreach($_child[$parent] as $k=>$v) {
            if($v['isUrl']==='yes') {
                $result[$k] = $v['url'];
            } else {
                $result[$k] = $uri .($uri ? '/' : ''). $v;
            }
			self::updateUriChild($result, $table, $result[$k], $k, $_child);
		}
	}


 	public static function updateUrl($table, $id) {
		$sql = cRegister::sql();
		$res = $sql->placeholder("SELECT path, uri, isUrl, url FROM ?t WHERE id=?", $table, $id);
		if(!$res) {
			$uri = '';
		} else {
			list($path, $uri, $isUrl, $url) = $res->fetchRow();
			if($isUrl==='yes') {
                $uri = $url;
			} else
			if($path) {
				$path = explode('][', substr($path, 1, -1));
				$res = $sql->placeholder("SELECT id, uri, isUrl, url FROM ?t WHERE id ?@ ORDER BY level", $table, $path)
								->fetchAssocAll();
	            $path = '';
	            foreach($res as $v) {
                    if($v['isUrl']==='yes') {
                        $path = $v['url'];
                    } else {
                        $path = $path . ($path ? '/' : ''). $v['uri'];
                    }
	            }
	            $uri = $path ? $path .'/'. $uri : $uri;
			}
		}

		$sql->placeholder("UPDATE ?t SET isUri=? WHERE id=?", $table, $uri, $id);

		$_child = $sql->placeholder("SELECT id, parent, uri, isUrl, url FROM ?t WHERE path LIKE '%[?i]%'", $table, $id)
		                ->fetchAssocAll('parent', 'id');
		$result = array();
		self::updateUriChild($result, $table, $uri, $id, $_child);
		return array($uri, $result);
	}

}

?>