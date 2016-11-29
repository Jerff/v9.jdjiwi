<?php


class driver_modul_edit_tree extends driver_modul_edit {


	// возвращает массив пути
	public function path() {
		$data = $this->getData();
		return $this->getDb()->path($data);
	}

	// какое-либо действия над данными перед загрузкой в формы
	// установка значения обьектов форм перед загрузкой данных
	protected function loadFormFilterParent() {
		return array(1);
	}

	public function loadForm() {
		parent::loadForm();
		$form = $this->form()->get();
		if(is_null($form->is('parent'))) return;

		$tree = $this->getDb()->loadFormData();

		$form->addElement('parent', 0, 'Верхний уровень');
		$this->loadFormTreeParent($tree);
		if($id = $this->id()->get()) $form->addOptions('parent', $id, 'disabled', 'disabled');
		else $form->select('parent', $this->getFilter('parent'));
		$form->addOptions('parent', $id, 'style', 'background-color: #000000; color: #FFFFFF; font-weight: bold');
	}

	protected function loadFormTreeParent(&$tree, $parent=0) {
		if(!isset($tree[$parent])) return;
		foreach($tree[$parent] as $id=>$value) {
			$name = '|' . str_repeat(' ----- | ', $value['level']+1). $value['name'];
			$this->form()->get()->addElement('parent', $id, $name);
			if(isset($tree[$id])) $this->loadFormTreeParent($tree, $id);
		}
	}


	protected function saveStart(&$send) {


		if($this->form()->get()->is('url') and $this->form()->get()->is('isUrl')) {
			cCommand::set('$formIsUrl');
		} elseif($this->form()->get()->is('uri')) {
			cCommand::set('$formIsUri');
		}
		$id = $this->id()->get();
		if(isset($send['parent']) or !$id) {
			if($id and $send['parent']==$id) exit;
			$parent = (int)get($send, 'parent');

			if($parent) {
				$row = $this->getDb()->getLevelPath($parent);
				$send['level'] = get($row, 'level')+1;
				$send['path'] = get($row, 'path') . "[$parent]";
				$path = $send['path'];
				if($path) {
					list($root) = explode('][', substr($path, 1, -1));
					$send['root'] = $root;
				} else {
					$send['root'] = 0;
				}
				unset($row);
			} else {
				$send['parent'] = 0;
				$send['level'] = 0;
				$send['path'] = '';
				$send['root'] = 0;
			}

			if($id) {
				$this->getDb()->setChildPath($send['path'], $id);
			}
		}

		if(!isset($send['parent']) and !$this->id()->get())
			$send['parent'] = $this->getFilter('parent');

		parent::saveStart($send);
	}



	public function copy($id, &$object) {
		if(empty($id)) return;

		$row = $this->getDb()->getDataRecord($id);
		if(!$row) return;

		$form = $this->form()->get();
		$form->select($row);
		$form->copyFile($row);
		unset($form);

		$row['id'] = 0;
		$this->id()->set(0);
		$this->save($row);
		$new_id = $this->id()->get();

		$path = $row['path'];
		$this->copyRecursively($object, $id, $new_id, $path."[$id]", $path."[$new_id]");
		return $new_id;
	}

	protected function copyRecursively(&$object, $parent_old, $parent_new, $path_old, $path_new) {
		$where = array('parent'=>$parent_old);
		$db = $this->getDb();
		$data = $db->getDataList($where);
		$form = $this->form()->get();
		foreach($data as $id=>$row) {
			$form->select($row);
			$form->copyFile($row);
			$path = $row['path'];
			$row['parent'] = $parent_new;
			$row['path'] = str_replace($path_old, $path_new, $path);

			$this->id()->set(0);
			$this->save($row);

			$new_id = $this->id()->get();
			$this->copyRecursively($object, $id, $new_id, $path ."[$id]", $row['path'] ."[$new_id]");
		}
	}

	public function getIdTree($id) {
		return $this->getDb()->getIdTree($id);
	}

	public function delete($id) {
		$id = $this->getDb()->getIdTree($id);
		parent::delete($id);
		return $id;
	}

	public function deleteChild($id) {

	}

}

?>