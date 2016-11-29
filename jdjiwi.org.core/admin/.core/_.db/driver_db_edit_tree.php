<?php


abstract class driver_db_edit_tree extends driver_db_edit {


	protected function getSort() {
		return array('pos');
	}

	protected function startSaveWhere() {
		return array('parent');
	}
	protected function getIsUrlExistsWhere() {
		return array('parent');
	}

	protected function getFilterParent() {
		return $this->getFilter('parent');
	}

	// какое-либо действия над данными перед загрузкой в формы
	// установка значения обьектов форм перед загрузкой данных
	protected function loadFormFilterParent() {
		return array(1);
	}

	public function loadFormData() {
		if($this->id()->get()) {
			return $this->sql()->placeholder("SELECT id, parent, level, name FROM ?t WHERE ?w AND (path IS NULL OR path NOT LIKE '%[?i]%') ORDER BY ?o", $this->getTable(), $this->loadFormFilterParent(), $this->id()->get(), $this->getSort())
									->fetchAssocAll('parent', 'id');
		} else {
			return $this->sql()->placeholder("SELECT id, parent, level, name FROM ?t ORDER BY pos", $this->getTable())
									->fetchAssocAll('parent', 'id');
		}
	}


	public function getLevelPath($id) {
		return $this->sql()->placeholder("SELECT level, path FROM ?t WHERE id=?", $this->getTable(), $id)
									->fetchAssoc();
	}

	public function getParentIsUri($id) {
		return $this->sql()->placeholder("SELECT isUri FROM ?t WHERE id=(SELECT parent FROM ?t WHERE id=?)", $this->getTable(), $this->getTable(), $id)
									->fetchRow(0);
	}

	public function getIsUri($id) {
		return $this->sql()->placeholder("SELECT isUri FROM ?t WHERE id=?", $this->getTable(), $id)
										->fetchRow(0);
	}

	public function setChildPath($path, $id) {
		$table = $this->getTable();
		$this->sql()->placeholder("UPDATE ?t SET path = CONCAT(?, SUBSTRING(path, LOCATE('[?i]', path)))  WHERE path LIKE '%[?i]%'", $table, $path, $id, $id);
		$this->sql()->placeholder("UPDATE ?t SET level = CHAR_LENGTH( path ) - CHAR_LENGTH( REPLACE( path , '[' , '' ) )  WHERE path LIKE '%[?i]%'", $table, $id);
	}


	public function getIdTree($listid) {
		if(!count($listid)) return $listid;
		$listid = (array)$listid;

		$str = '';
		foreach($listid as $id)
			$str .= "OR path LIKE '%[". (int)$id ."]%'";

		return $this->sql()->placeholder("SELECT id FROM ?t WHERE id ?@ $str", $this->getTable(), $listid)
									->fetchRowAll(0, 0);
	}


	// запись данных формы с базу
	protected function saveEnd($id, $send) {
		$old = $this->id()->get();
		parent::saveEnd($id, $send);
		if(isset($send['parent']) or isset($send['name'])) {
			$this->command()->reloadView();
		}
		if(isset($send['visible'])) {
			cmfAdminTree::updateVisible($this->getTable(), $id);
		}
		if(cCommand::is('$formIsUri') and (isset($send['uri']) or isset($send['parent']))) {
			list($isUri, $result) = cmfAdminTree::updateUri($this->getTable(), $id);
			if($result) {
			    foreach($result as $k=>$uri) {
				    $this->id()->set($k);
    				$this->save(array('isUri'=>$uri));
	    		}
	        }
			$this->id()->set($id);
		}
		if(cCommand::is('$formIsUrl') and (isset($send['uri']) or isset($send['parent']) or isset($send['url']) or isset($send['isUrl']))) {
			list($isUri, $result) = cmfAdminTree::updateUrl($this->getTable(), $id);

			if($result) {
			    foreach($result as $k=>$uri) {
				    $this->id()->set($k);
    				$this->save(array('isUri'=>$uri));
	    		}
	        }
			$this->id()->set($id);
		}
		$this->id()->set($old);
	}

	/* функции для дерева */
	// возвращает массив пути
	public function path(&$data) {
		if($id=$this->id()->get()) {
			$path = $data['path'] .'['. $id .']';
			$path = explode('][', substr($path,1,-1));
		} else {
			if($parent = $this->getFilterParent()) {
				list($path) = $this->sql()->placeholder("SELECT path FROM ?t WHERE id=?", $this->getTable(), $parent)
										->fetchRow();
				$path = $path .'['. $parent .']';
				$path = explode('][',substr($path,1,-1));
			} else $path = array();
			$path[] = 0;
		}

		$name = $this->sql()->placeholder("SELECT id, name FROM ?t WHERE id ?@", $this->getTable(), $path)
						->fetchRowAll(0, 1);

		$path_name = array();
		foreach($path as $value)
			$path_name[$value]['name'] = isset($name[$value]) ? $name[$value] : 'Новая запись';

		return $path_name;
	}




}

?>