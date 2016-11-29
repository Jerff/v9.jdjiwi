<?php


class cmfUpdateAccess {

	public static function start(){
		cmfUpdateTable::exportDumpPages();
		self::updateMain();
		self::updateAdmin();
		cCompile::update()->start();
		cAdmin::cache()->clear();
	}


	// генерация страниц для админки
	public static function updateAdmin() {
		cConfig::ignoreUserAbort(true);
		$sql = cRegister::sql();

		$res = $sql->placeholder("SELECT id, parent, name FROM ?t WHERE type IN('tree', 'list') AND visible='yes' ORDER BY pos", db_pages_admin);
		$_tree_admin = array();
		$id = array();
		while($row = $res->fetchAssoc()) {
			$id[] = $row['id'];
			$_tree_admin[$row['parent']][$row['id']]['name'] = $row['name'];
		}
		$res->free();

		$res = $sql->placeholder("SELECT * FROM ?t WHERE parent ?@ AND type IN('pages', 'pagesSystem') AND path!='' AND visible='yes' ORDER BY pos", db_pages_admin, $id);
		$_admin = array();
		$_templates = array();
		$i =0;
		while($row = $res->fetchAssoc()) {
			$data = array();
			$data['small_name'] = $row['small_name'];

			$templates = $row['templates'];
			if($templates) {
				if(!isset($_templates[$templates])) {
					$_templates[$templates] = $i;
					$data['t'] = $_templates[$templates];
					$i++;
				} else {
					$data['t'] = $_templates[$templates];
				}
			}

			if($row['type']==='pages') {
				$data['url'] = "'". ($row['url'] ? $row['url'] : substr($row['small_name'], 6)) ."'";
			}

			if(!empty($row['pattern'])) $data['pattern'] = "array('#^".preg_replace("#([ |\n]+)#","$#','#^", $row['pattern']) ."$#')";

			$data['path']="'{$row['php_path']}'";
			$_admin[$row['parent']][$row['id']] = $data;
		}
		$res->free();


		$res = $sql->placeholder("SELECT id,parent,name FROM ?t WHERE type IN('tree', 'list') AND visible='yes' ORDER BY pos", db_pages_main);
		$_tree_main = array();
		$id = array();
		while($row = $res->fetchAssoc()) {
			$id[] = $row['id'];
			$_tree_main[$row['parent']][$row['id']]['name'] = $row['name'];
		}
		$res->free();

		$res = $sql->placeholder("SELECT * FROM ?t WHERE parent ?@ AND type='pages' AND path!='' AND visible='yes' ORDER BY pos", db_pages_main, $id);
		$_main = array();
		while($row = $res->fetchAssoc()) {
			$data = array();
			$data['small_name'] = $row['small_name'];
			//$data['name']=$row['name'];

			$data['part'] = "'{$row['part']}'";
			if(!empty($row['url'])) $data['url']="'{$row['url']}'";
			$_main[$row['parent']][$row['id']] = $data;
		}
		$res->free();


		cConfig::ignoreUserAbort(true);
		$str = self::createUrlTreeAdmin($_tree_admin, $_admin, $_access);
		$str .= "\n". self::createUrlTreeAdminMain($_tree_main, $_main);

		$str .="\n\$t = array();";
		foreach($_templates as $k=>$v) {
            $str .="\n\$t[$v] = '$k';";
		}

		$str = "<?php
$str
cmfPages::select(\$p, \$t);
?>";
		file_put_contents('_config/pageAdmin.php', $str);
		cConfig::ignoreUserAbort(false);
	}

	public static function createUrlTreeAdmin(&$tree, &$pages, &$_access, $parent=0) {
		if($parent) {
			$str = '';
		} else {
			$str = "\n\$p = array();";
		}
		foreach($tree[$parent] as $key=>$value) {
			$str .= "\n// --------- {$value['name']} ---------";
			if(isset($tree[$key])) {
				$str .= self::createUrlTreeAdmin($tree, $pages, $_access, $key);
			}
			if(isset($pages[$key])) {
				foreach($pages[$key] as $key2=>$value2) {
					$str .= "\n\$p['{$value2['small_name']}']=array(";
					unset($value2['small_name']);
					$sep = '';
					foreach($value2 as $key3=>$value3) {
						$str .= $sep ."\n'$key3'=>$value3";
						$sep = ',';
					}
					$str .= "\n);\n";
				}
			}
			$str .= "// --------- /{$value['name']} ---------\n";
		}
		return $str;
	}


	public static function createUrlTreeAdminMain(&$_tree, &$_pages, $parent=0) {
		$str = '';
		foreach($_tree[$parent] as $key=>$value) {
			$str .= "\n// --------- {$value['name']} ---------";
			if(isset($_tree[$key])) {
				$str = self::createUrlTreeMain($_tree, $_pages, $key);
			}
			if(isset($_pages[$key])) {
				foreach($_pages[$key] as $key2=>$value2) {
					$str .= "\n\$p['{$value2['small_name']}']=array(";
					unset($value2['small_name']);
					$sep = '';
					foreach($value2 as $key3=>$value3) {
						$str .= $sep ."\n'$key3'=>$value3";
						$sep = ',';
					}

					$str .= "\n);\n";
				}
			}
			$str .= "// --------- /{$value['name']} ---------\n";
		}
		return $str;
	}




	// генерация страниц для морды
	public static function updateMain() {
		cConfig::ignoreUserAbort(true);
		$sql = cRegister::sql();

		$res = $sql->placeholder("SELECT id, parent, name FROM ?t WHERE type IN('tree', 'list') AND visible='yes' ORDER BY pos", db_pages_main);
		$tree_main = array();
		$id = array();
		while($row = $res->fetchAssoc()) {
			$id[] = $row['id'];
			$tree_main[$row['parent']][$row['id']]['name'] = $row['name'];
		}
		$res->free();

		$res = $sql->placeholder("SELECT * FROM ?t WHERE parent ?@ AND type='pages' AND path!='' AND visible='yes' ORDER BY pos", db_pages_main, $id);
		$main = array();
		$_templates = array();
		$i =0;
		while($row = $res->fetchAssoc()) {
			$data = array();
			$data['small_name'] = $row['small_name'];

			$templates = $row['templates'];
			if($templates) {
				if(!isset($_templates[$templates])) {
					$_templates[$templates] = $i;
					$data['t'] = $_templates[$templates];
					$i++;
				} else {
					$data['t'] = $_templates[$templates];
				}
			}

			$data['part'] = "'{$row['part']}'";
			if(!empty($row['url'])) $data['url']="'{$row['url']}'";
			if(!empty($row['pattern'])) $data['pattern'] = "array('#^".preg_replace("#([ \n]+)#","$#','#^", $row['pattern']) ."$#')";
			$data['path']="'{$row['php_path']}'";

			if($row['cacheBrousers']==='no')	$data['brousers']='false';
			if($row['cache']==='no')			$data['!cache']='true';
			if($row['cacheParam1']==='yes' or $row['cacheParam2']==='yes')	{
				$data['param']='true';
			} elseif($row['cacheParam1']==='yes') {
				$data['param1']='true';
			} elseif($row['cacheParam2']==='yes') {
				$data['param2']='true';
			}
			if($row['cacheMain']==='yes')		$data['isMain']='true';
			if($row['cacheUrl']==='no')			$data['noUrl']='true';
			if($row['cacheRequestUri']==='yes')	$data['Request']='true';

			$main[$row['parent']][$row['id']] = $data;
		}
		$res->free();



		// сохраняем критичные данные
		cConfig::ignoreUserAbort(true);

		list($main, $url) = self::createUrlTreeMain($tree_main, $main);

		$url .="\n\$t = array();";
		foreach($_templates as $k=>$v) {
            $url .="\n\$t[$v] = '$k';";
		}

		$str = "<?php
$main
$url
cmfPages::select(\$p, \$n, \$pr, \$t);

?>";
		file_put_contents('_config/pageMain.php', $str);
		cConfig::ignoreUserAbort(false);
	}

	public static function createUrlTreeMain(&$_tree, &$_pages, $parent=0) {
		if($parent) {
			$str = "\n";
			$pages = "\n";
			$preg = "\n";

		} else {
			$str = "\n\$p = array();";
			$pages = "\$n = array();\n";
			$preg = "\$pr = array();\n";

		}

		foreach($_tree[$parent] as $key=>$value) {
			$str .= "\n// --------- {$value['name']} ---------";
			if(isset($_tree[$key])) {
				list($str, $pages) = self::createUrlTreeMain($_tree, $_pages, $key);
			}
			if(isset($_pages[$key])) {
				foreach($_pages[$key] as $key2=>$value2) {
					if(isset($value2['url'])) {
						if(!preg_match('/(\([0-9]+\))/', $value2['url'])) {
							$pages .= "\$n[{$value2['part']}][{$value2['url']}]='{$value2['small_name']}';\n";

						}
					}

					if(isset($value2['pattern'])) {
						$preg .= "\$pr[{$value2['part']}]['{$value2['small_name']}']={$value2['pattern']};\n";
						unset($value2['pattern']);
					}

					$str .= "\n\$p['{$value2['small_name']}']=array(";
					unset($value2['small_name']);
					$sep = '';
					foreach($value2 as $key3=>$value3) {
						$str .= $sep ."\n'$key3'=>$value3";
						$sep = ',';
					}

					$str .= "\n);\n";
				}
			}
			$str .= "// --------- /{$value['name']} ---------\n";
		}
		return array($str, $pages . $preg);
	}

}

?>
