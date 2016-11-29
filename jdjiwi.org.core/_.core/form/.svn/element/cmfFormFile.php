<?php


class cmfFormFile extends cmfFormElement {

	protected $UPLOAD_FILE = '|7z|aiff|asf|avi|bmp|csv|doc|fla|flv|gif|gz|gzip|jpeg|jpg|mid|mov|mp3|mp4|mpc|mpeg|mpg|ods|odt|pdf|png|ppt|pxd|qt|ram|rar|rm|rmi|rmvb|rtf|sdc|sitd|swf|sxc|sxw|tar|tgz|tif|tiff|txt|vsd|wav|wma|wmv|xls|xml|zip|';
	protected $size = null;
	protected $upload = true;
	protected $fileSize = null;
	protected $path = null;
	protected $watermark = false;

	protected function init($o) {
		if(isset($o['size']) and is_array($o['size']) and count($o['size'])==2)
			$this->setSize($o['size']);
		if(isset($o['path'])) $this->setPath($o['path']);
		if(isset($o['fileSize'])) $this->fileSize = $o['fileSize'];
		if(isset($o['noUpload'])) $this->setUpload(false);

        if(isset($o['watermark'])) $this->setWatermark();

		parent::init($o);
	}


	public function setWatermark() {
		$this->watermark = true;
	}
	public function getWatermark() {
		return cSettings::read('watermark', 'enable')=='yes' ? $this->watermark : false;
	}

	public function setSize($size) {
		$this->size = $size;
	}
	public function getSize() {
		return $this->size;
	}

	public function setPath($path) {
		$this->path = $path;
	}
	public function getPath() {
		return $this->path;
	}
	public function getFolder() {
		return cmfWWW . $this->getPath();
	}

	protected function setUpload($s) {
		$this->upload = $s;
	}
	protected function isUpload(){
		return $this->upload;
	}


	public function html($param, $style='') {
		return "<input type=\"file\" name=\"". ($name=$this->getId()) ."\" id=\"{$name}\" type=\"{file}\" {$style} />";
	}


	public function htmlOld() {
		return null;
	}

	public function isFile() {
		return true;
	}

	protected function jsUpdateValue() {
        return '';
	}
	public function jsUpdateOld() {
		return '';
	}


	public function processing($data, $old, $upload) {
        $name = $this->getId();
        $files = $this->form()->getRequest()->getFiles($name);
		$file = $this->uploadFile($name, $files);
        if($file and $upload) {
        	$file = $this->upload($this->getElementId(), $files);
        }
        return $file;
	}

	protected function upload($name, $file) {
		$resize = $this->getSize();
		if(!is_null($resize)) {
			list($width, $height) = $resize;
			cImage::resize($file['tmp_name'], $width, $height);
            if($this->getWatermark()) {
                cImage::watermark($file['tmp_name']);
            }
		}

		if($this->isUpload()) {
			$name = cFile::upload($this->getFolder(), $file);
		} else {
			$name = $file['tmp_name'];
		}

		return $name;
	}

	protected function uploadFile($name, $file) {
		if(!isset($file['error'])) return null;
		if($file['error']!==UPLOAD_ERR_OK) {
			$f_error = $file['error'];
			switch($file['error']) {
				case UPLOAD_ERR_NO_FILE:
					if($this->NotEmpty()) {
						cmfFormError::set("Файл не был загружен");
					}
					break;

				case UPLOAD_ERR_PARTIAL:
					cmfFormError::set("Файл был получен только частично");
					break;

				case UPLOAD_ERR_FORM_SIZE:
					cmfFormError::set("Размер загружаемого файла превысил значение MAX_FILE_SIZE, указанное в HTML-форме");
					break;

				case UPLOAD_ERR_INI_SIZE:
					cmfFormError::set("Размер принятого файла превысил максимально допустимый размер, который задан директивой upload_max_filesize конфигурационного файла php.ini");
					break;
			}
			return null;
		}

		$sif = preg_replace('`.*\.([^.]*)$`', '$1', $file['name']);
		if(!$sif) {
			cmfFormError::set("разрешение не поддерживается");
			return null;
		}
		if(strpos($this->UPLOAD_FILE, '|'. $sif .'|')===false) {
            cmfFormError::set("разрешение не поддерживается");
			return null;
		}

		if($this->fileSize) {
			$size = $this->fileSize;
			if($file['size'] > $size*1024*1024) {
				cmfFormError::set("Размер загружаемого файла превысил допустимое значение");
				return null;
			}
		}

		if($this->isUpload()) {
			return $file['tmp_name'];
		} else {
			return serialize($file);
		}
	}


	public function deleteFile(&$row) {
		cFile::unlink($this->getFolder() . $this->getValue());
	}

	public function copyFile(&$row, $name) {
		$row[$name] = cFile::copy($this->getFolder() . $this->getValue(), $this->getFolder() . $this->getValue());
	}
}

?>