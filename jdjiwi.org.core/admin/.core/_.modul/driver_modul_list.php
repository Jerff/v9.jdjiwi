<?php


abstract class driver_modul_list extends driver_modul_edit {


	public function getNameID($id) {
		if(!$id) $id = '{0}';
		return $this->getName() . $id;
	}



	public function loadForm2() {
	}
	public function loadFormNewLine($index) {
	}


	// заполнение формы данными из базы
	public function runForm() {
		if(!$id = $this->id()->get()) return;
		$this->runData();
		$this->selectForm($this->getDataId($id));
	}


	// вызывается выбора только для одной записи (iframe, new_line)
	public function runData() {
		$id = array($this->id()->get());
		$this->runList($id);
	}

	// выборка данных записи из базы
	public function runList($id=null, $offset=null, $limit=null) {
		$this->loadForm();

		$data = $this->getDb()->runList($id, $offset, $limit);
		$this->setData($data);

		return array_keys($data);
	}

	public function getTotal() {
		return $this->getDb()->getTotal();
	}


	public function currentForm($id) {
		$this->form()->get()->changeName($this->getNameID($id));
	}

	public function &currentList($index, $id) {
		$form = $this->form()->get();
		$form->changeName($this->getNameID($index));
		$data = $this->getDataId($id);
		$this->selectForm($data);

		$current = array();
		$current[] = $form;
		$current[] = new cmfData($data);

		return $current;
	}


	public function deleteFile($element) {
		$id = $this->id()->get();
		$data = $this->getDb()->runList($id);
		$form = $this->form()->get();
		$form->select($data[$id]);

		$send = array();
		$form->deleteFile($element, $send);
		$this->save($send);
	}


	// обновление форм
	public function updateIsErrorList($listId, &$isError) {
		$this->loadForm();
		$form = $this->form()->get();

		foreach($listId as $index=>$id) {
			$form->changeName($this->getNameID($index));
    		$this->loadFormRunning($id);
			$data = $form->handler();
			if($form->isError()) {
				$isError = true;
				break;
			}
			$this->updateIsErrorData($data, $isError);
		}
	}

	// загружаем форму во выремя выполнения
	public function loadFormRunning($id=0) {
	}
	public function getData() {
		return $this->getDataId($this->id()->get());
	}

	public function updateListOk($listId=null) {
		$form = $this->form()->get();
		$data = $this->getDb()->runList($listId);

		foreach($listId as $index=>$id) {
			$this->id()->set($id);
			$form->changeName($this->getNameID($index));
			$send = $form->processing();
			if(isset($data[$id])) {
				$this->selectForm($data[$id]);
				foreach($send as $k=>$v)
					$form->deleteFile($k, $data[$id]);
			}

			$this->save($send);
			if(isset($send['pos'])) $this->command()->reloadView();
		}
		$this->runList($listId);
	}

	public function updateErrorList($listId) {
		$isError = false;
		$js = '';
		$form = $this->form()->get();
		foreach($listId as $index=>$id) {
			$this->id()->set($id);
			$form->changeName($this->getNameID($index));
			$data = $form->handler();
			$this->updateIsErrorData($data, $isError);

			$js .= $form->jsUpdate(false);
		}
		return $js;
	}


	public function getJsSetDataList($name, &$modul, $listId=null) {
		$js = '';
		$form = $this->form()->get();

		if(is_null($listId)) $listId = (array)$this->id()->get();
		foreach($listId as $index=>$id) {
            $this->id()->set($id);
			$form->changeName($this->getNameID($index));
			$this->selectForm($this->getDataId($id));
            $this->loadForm2();

			$file = $this->getListFile();
			foreach($file as $f) {
				$modul->getJsFile($name, $f);
			}
			$js .= $form->jsUpdate();
		}
		return $js;
	}





	public function newLine($index, &$data) {
		$this->getDb()->loadData($data);
		$line = array('data'=>$data);

		$form = $this->form()->get();
		$form->changeName($this->getNameID($index));
		$this->selectForm($data);
		return $form;
	}

}

?>