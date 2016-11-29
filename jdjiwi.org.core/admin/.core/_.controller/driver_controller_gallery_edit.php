<?php


abstract class driver_controller_gallery_edit extends driver_controller_edit {

    protected function init() {
        parent::init();
		$this->access()->writeAdd('updatePreview');
	}

	protected function updatePreview() {
	    $this->modul()->updatePreview();
	    $this->ajax()->script("cmf.admin.gallery.show();");
	}

    public function getGalleryPath() {
        return $this->modul()->getGalleryPath();
    }
	public function getGallerySize() {
	    return $this->modul()->getGallerySize();
	}
	public function getGalleryId() {
	    return $this->modul()->getGalleryId();
	}
}

?>
