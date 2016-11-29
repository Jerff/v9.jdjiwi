<?php


abstract class driver_modul_gallery_edit extends driver_modul_edit {


    protected function saveStart(&$send) {
        parent::saveStart($send);
        if(isset($send['main'])) {
            $this->command()->reloadView();
        }
		if($this->id()->get()) {
    		$this->command()->reloadView();
        }
	}


    private $path = null;
    protected function setGalleryPath($path) {
        $this->path = $path;
    }
    public function getGalleryPath() {
        return $this->path;
    }

    private $size = null;
    protected function setGallerySize($w, $h) {
        $this->size = array($w, $h);
	}
	public function getGallerySize() {
	    return $this->size;
	}

	public function getGalleryId() {
	    return 'image_small';
	}

    public function updatePreview() {
        $real = isset($_POST['real']);
        $x1 = (int)get($_POST, 'x1');
        $x2 = (int)get($_POST, 'x2');
        $y1 = (int)get($_POST, 'y1');
        $y2 = (int)get($_POST, 'y2');
        $w = (int)get($_POST, 'w');
        if(!$x2 or !$y2) return;

	    $data = $this->runData();
	    if(empty($data['image_main'])) return;

	    $main = cWWWPath . $this->getGalleryPath() . $data['image_main'];
        $size = getimagesize($main);
	    $oWidth = $size[0];
	    $oHeight = $size[1];

        list($width, $height) = $this->getGallerySize();
	    $scaleX = $oWidth/($w ? $w : view_gallery::width);
	    $x1 = ceil($x1*$scaleX);
	    $x2 = ceil($x2*$scaleX);
	    $y1 = ceil($y1*$scaleX);
	    $y2 = ceil($y2*$scaleX);

    	$w = $x2-$x1;
    	$h = $y2-$y1;
	    if(!$w or !$h) return;
	    if(!$real) {
            if($width/$w!=$height/$h) {
                $max = $w/$width;
                if($h/$height>$max) {
                    $max = $h/$height;
                }
                $x2 = $x1 + ceil($width*$max);
                $y2 = $y1 + ceil($width*$max);

                $w = $x2-$x1;
    	        $h = $y2-$y1;
            }
	    }

	    $this->updatePreviewAll(array($main, $x1, $y1, $w, $h, $oWidth, $oHeight), $data, $width, $height);
        $image = cBaseImgUrl . $this->getGalleryPath() . $data[$this->getGalleryId()] .'?'. time();
        $this->ajax()->script("$('#galleryPreviewId img, #preview". $this->id()->get() ."').attr('src', '{$image}');");
	}

	protected function updatePreviewAll($res, $data, $width, $height) {
         $this->updateThumbnail($res, $data[$this->getGalleryId()], $width, $height);
	}

	protected function updateThumbnail($res, $small, $width, $height) {
        list($main, $x1, $y1, $w, $h, $oWidth, $oHeight) = $res;
	    $small = cWWWPath . $this->getGalleryPath() . $small;
        cFile::unlink($small);
	    cFile::copy($main, $small);
	    cImage::thumbnail($small, $oWidth, $oHeight, $x1, $y1, $w, $h, $width, $height);
	}

}

?>
