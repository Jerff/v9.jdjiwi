<?php


class cmfFormImage extends cmfFormFile {

	protected $UPLOAD_FILE = '|jpg|gif|jpeg|png|bmp|';
	protected $img = null;
	protected $serialize = true;
	protected $watermark = false;

	protected function init($o) {
		if(isset($o['size']) and is_array($o['size'])) $this->setSize($o['size']);
		if(isset($o['addField'])) $this->setSerialize(false);

		parent::init($o);
	}

	public function setSerialize($value) {
		$this->serialize = (bool)$value;
	}
	public function getSerialize() {
		return $this->serialize;
	}


	// выбор активного элемента | установка значения
	public function select($value, $name=null) {
		if(empty($value)) return;
		$this->img = $name;
		parent::select(unserialize($value));
	}


	public function getValue() {
		$name = $this->img .'_main';
		$value = $this->getValues();
		return get2($value, 'image', $name);
	}

	public function isFile() {
		return true;
	}

	protected function upload($name, $file) {
		if($this->isUpload()) {
			$file = cFile::upload($this->getFolder(), $file);
		} else {
			return $file['tmp_name'];
		}

		$send = array();
		$send['image'] = array($name. '_main'=>$file);
		$path = $this->getFolder();
		foreach($this->getSize() as $img=>$size) {
			if(count($size)!=2) continue;
			list($width, $height) = $size;
			if($img==='main') {
				if(!empty($_POST['isReize'. $this->getId()])) {
				    cImage::resize($path. $file, $width, $height);
				}
				if($this->getWatermark()) {
                    cImage::watermark($path. $file);
				}
				$send['image'][$name .'_'. $img] = $file;
			} else {

				$newFile = $img .'/'. $file;
				if(cFile::copy($path . $file, $path . $newFile)) {
					cImage::resize($path . $newFile, $width, $height);
    				$send['image'][$name .'_'. $img] = $newFile;
				}
			}
		}
		foreach($send['image'] as $key=>$file) {
			$size = getimagesize($path . $file);
			$send['size'][$key]['width'] = $size[0];
			$send['size'][$key]['height'] = $size[1];
		}
		if($this->getSerialize()) return serialize($send);

		$image = array($name=>serialize($send));
		foreach($send['image'] as $key=>$file)
			$image[$key] = $file;
		return $image;
	}

	public function deleteFile(&$row) {
		$value = $this->getValues();
		if(isset($value['image']))
			foreach($value['image'] as $key=>$file) {
				if(!$this->getSerialize()) $row[$key] = null;
				cFile::unlink($this->getFolder() . $file);
			}
	}

	public function copyFile(&$row, $name) {
		$path = $this->getPath();
		$value = $this->getValues();
		if(isset($value['image']))
			foreach($value['image'] as $key=>$file)
			$value['image'][$key] = cFile::copy($this->getPath() . $this->getValue(), $this->getPath() . $this->getValue());

		$row[$name] = serialize($value);
		if($this->getSerialize()) return;

		foreach($value['image'] as $key=>$file)
			$row[$key] = $file;
	}

	public function html($param, $style='') {
		$size = $this->getSize();
		if(empty($size['main'][0]) and empty($size['main'][1])) {
		    return parent::html($param, $style);
		}
		return '<label><input name="isReize'. $this->getId() .'" type="checkbox" checked="true">&nbsp;Масштабировать изображение</label><br />'.
		       parent::html($param, $style);
	}

}

?>