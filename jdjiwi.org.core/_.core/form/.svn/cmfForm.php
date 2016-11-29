<?php


cLoader::library('form/cmfFormConfig');
cLoader::library('form/cmfFormError');

cLoader::library('form/library/cmfFormLibJs');
cLoader::library('form/library/cmfFormLibString');
cLoader::library('form/library/cmfFormLibFilter');
cLoader::library('form/library/cmfFormLibReform');
cLoader::library('form/library/cmfFormLibFile');
cLoader::library('form/library/cmfFormLibImage');

cLoader::library('form/cmfFormElement');
cLoader::library('form/element/cmfFormText');
cLoader::library('form/element/cmfFormKcaptcha');
cLoader::library('form/element/cmfFormPassword');
cLoader::library('form/element/cmfFormTextarea');
cLoader::library('form/element/cmfFormCheckbox');
cLoader::library('form/element/cmfFormSelect');
cLoader::library('form/element/cmfFormRadio');
cLoader::library('form/element/cmfFormFile');
cLoader::library('form/element/cmfFormImage');




class cmfForm implements Iterator {


	private	$error = null;

	private	$options = array();

	private $object = array();
	private $request = null;


	function __construct($url='', $name='itemForm', $o=null) {
		$this->setOption('url', $url);
		$this->setOption('name', $name);
		$this->setOption('method', get($o, 'method', 'post'));
		$this->setOption('color', get($o, 'color', '#FCD081'));
		$this->setOption('color', get($o, 'security'));

		$p = array();
		if(isset($o['uploadFile'])) $p[] = 'enctype="multipart/form-data"';
		$this->setOption('options', implode(' ', $p));
	}

    public function __sleep() {
        return array('options', 'object');
    }

	// новая форма
	function newForm() {
		$this->error = null;
		$this->object = array();
	}

	// перелпередеоение свойств формы
	public function reset() {
		foreach($this->getObjectAll() as $object) {
			$object->reset();
		}
		$this->clearError();
	}

        //смена имени формы - новые данные для формы
	public function changeName($name) {
		$this->setOption('name', $name1 = preg_replace('~([^a-z0-9\_\-])~i', '_', $name));
		foreach($this->getObjectAll() as $object) {
			$object->reset();
		}
		$this->clearError();
	}


	public function resetElement() {
		if(!func_num_args()) {
			foreach($this->getObjectAll() as $object) {
				$object->resetElement();
			}
		} else {
			$objectAll = $this->getObjectAll();
			foreach(func_get_args() as $value) {
				if($objectAll[$value]) {
					$objectAll[$value]->resetElement();
				}
			}
		}
	}


	// очищение от ненужных свойств
	public function free() {
		unset($this->error, $this->request);
	}


    //усправление свойсвами формы
	public function setOption($n, $v) {
		$this->options[$n] = $v;
	}
	public function getOption($n=null) {
		return $n ? get($this->options, $n) : $this->options;
	}


	// имя формы
	public function getId($name=null) {
		return $this->getOption('name') .($name ? '_'. $name : '');
	}


	// --------------- установка корректных внутренних переменных фильтров ---------------
	public function setRequest() {
		$this->request = cmfRequest::singelton();
	}
	public function getRequest() {
		return $this->request;
	}
	// --------------- /установка корректных внутренних переменных фильтров ---------------


	private function getSecretKey() {
		return sha1(cmfSalt . session_id());
	}
	public function viewSecurity() {
		return '<input name="'. $this->getId('Security') .'" type="hidden" value="'. $this->getSecretKey() .'">';
	}
	private function isValidSecurity() {
		if($this->getOption('security')) {
//            pre($_SERVER);
            return get($_POST, $this->getId('Security'))===$this->getSecretKey();
        } else {
            return true;
        }
	}






	// ------------- Установить, вернуть Элементы формы -------------
	public function add($name, $object) {
		if(is_subclass_of($object, 'cmfFormElement')) {
			$object->setForm($this, $name);
			$this->object[$name] = $object;
		}
	}
	private function &getObjectAll() {
		return $this->object;
	}
	public function isObject($name) {
		return isset($this->object[$name]);
	}
	public function &get($name) {
		return $this->object[$name];
	}




	// ------------- /Установить, вернуть Элементы формы -------------





	// ------------- установка значений элементов -------------
	// заполнение доп данными для массивов
	public function addElement($name, $key, $value) {
		$this->get($name)->addElement($key, $value);
	}

	// заполнение доп данными для массивов
	public function addOptions($element, $key, $name, $value) {
		$object = $this->get($element);
		if($object and (is_subclass_of($object, 'cmfFormSelect') or get_class($object)==='cmfFormSelect')) {
			$object->setOptions($key, $name, $value);
		}
	}

	public function selectAll($data) {
		foreach($this->getObjectAll() as $name=>$object) {
			if(isset($data[$name])) {
				$object->select($data[$name], $name);
			}
            $object->selectAll($data);
		}
	}

	public function select($name, $value) {
		if($this->isObject($name))
    		$this->get($name)->select($value);
	}

	public function getValue($name) {
		return $this->get($name)->getValue();
	}
	// ------------- /установка значений элементов -------------



	// ------------- Обрабока Полученных Данных Из Формы -------------
	// возврат всех ошибок разбора данных
	public function getError() {
		return $this->error;
	}
	public function isError() {
		return (bool)$this->error;
	}
	public function isErrorElement($name) {
		return isset($this->error[$name]);
	}
	public function delError($name) {
		unset($this->error[$name]);
	}
	public function getErrorElement($n) {
		return get($this->error, $n);
	}
	private function clearError() {
		$this->error = null;
	}
	public function setError($name, $error=null) {
		if(!$error) $error = cmfFormError::get();
		$this->error[$name] = str_replace('{element}', $name, $error);
	}
	// ------------- /Обрабока Полученных Данных Из Формы -------------



	// ------------- разбор элементов формы -------------
	// вернуть обработанные данные
	public function handler($old=false) {
		return $this->processingForm($old, false);
	}

	public function processing($old=true, $upload=true) {
		$send = $this->processingForm($old, $upload);
		$this->selectAll($send);
		return $send;
	}

	private function &processingForm($old=true, $upload=true) {
		$this->reset();

		$r = $this->getRequest();
		switch($this->getOption('method')) {
			case 'get':
				$data = $r->getGetAll();
				break;

			case 'post':
			default:
				$data = $r->getPostAll();
				break;
		}

		$sent = array();
        if($this->isValidSecurity())
		foreach($this->getObjectAll() as $name=>$object) {
			$value = $object->processing($data, $old, $upload);
			if(cmfFormError::is()) {
				$this->setError($name);
			}
			if(!is_null($value)) {
				if(is_array($value)) {
					$sent = array_merge($sent, $value);
				} else {
					$sent[$name] = $value;
				}
			}

		}

		return $sent;
	}

	public function handlerElement($name, $old=false) {
		return $this->processingElement($name, $old);
	}

	public function processingElement($name, $old=true) {
		$r = $this->getRequest();
		switch($this->getOption('method')) {
			case 'get':
				$data = $r->getGetAll();
				break;

			case 'post':
			default:
				$data = $r->getPostAll();
				break;
		}
		cLog::error($name);
		return $this->get($name)->processing($data, $old, null);
	}


	public function processingName(&$data) {
		$send = array();
		foreach($this->getObjectAll() as $name=>$object) {
			if(!empty($data[$name])) {
                $v = $data[$name];
                if($this->get($name)->isTypeElement('select')) {
                     $d = $this->get($name)->getValuesAll();
                     $v = get($d, $v, $v);
                }
                if($object->getName()) {
                    if($object->isTypeElement('checkbox')) {
                        if($object->getYes()==$v) {
                            $send[$object->getName()] = null;
                        }
                    } else {
                        $send[$object->getName()] = $v;
                    }
                }
			}
		}
		return $send;
	}

	// удаление файлов формы
	public function deleteFileAll() {
		$row = array();
		foreach($this->getObjectAll() as $name=>$object) {
			if($object->isFile()) {
			    $row[$name] = null;
			    $object->deleteFile($row);
			}
		}
		return $row;
	}

	public function deleteFile($name, &$row) {
		if($this->isObject($name) and $this->get($name)->isFile()) {
			$row[$name] = null;
			$this->get($name)->deleteFile($row);
		}
	}

	// копирование файлов формы
	public function copyFile(&$row) {
		foreach($this->getObjectAll() as $name=>$object) {
			$object->copyFile($row, $name);
		}
	}
	// ------------- /разбор элементов формы -------------





	// ------------- создание html кода -------------
	public function label($name, $value, $style='') {
		$this->get($name)->label($value);
		return $this->html($name, $style);
	}
	public function viewLabel($name, $value) {
		$this->get($name)->label($value);
		return $this->get($name)->html('label');
	}
	public function viewError($name) {
		return $this->get($name)->viewError();
	}

	public function html($name, $style='') {
		return $this->get($name)->htmlView(null, $style);
	}

	public function checkbox($name, $style='') {
		return $this->get($name)->htmlView('checkbox', $style);
	}

	public function radio($name, $param, $style='') {
		$param = array($param, 'radio');
		return $this->get($name)->htmlView($param, $style);
	}
	public function radioLabel($name, $param, $value, $style='') {
		$param = array($param, 'label', $value);
		return $this->get($name)->htmlView($param, $style);
	}




	public function &getListFile() {
		$file = array();
		foreach($this->getObjectAll() as $name=>$object) {
			if($object->isFile()) {
				$file[] = $name;
			}
		}
		return $file;
	}

	// ------------- /создание javascript кода -------------
	// заполняем элементы формы с помощью Javascript
	public function jsUpdate($old=true) {
		$js='';
		foreach($this->getObjectAll() as $name=>$object) {
			$js .= $object->jsUpdate();
			if($old) {
				$js .= $object->jsUpdateOld();
			}
		}
		return $js;
	}
	// ------------- /создание JavaScript кода -------------




	public function isForm() {
		return (bool)$this->object;
	}
	// --------------- интерфейс Iterator к foreach ---------------
	public function rewind() {
		reset($this->object);
	}

	public function current() {
		return null;//$this->getOption['name'];
	}

	public function key() {
		return key($this->object);
	}

	public function next() {
		return next($this->object);
	}

	public function valid() {
		return current($this->object)!==false;
	}
	// --------------- интерфейс Iterator к foreach ---------------
}
?>
