<?php

$import = $this->load('import', '_backup_site_import_controller');
$list = $this->load('list', '_backup_site_list_controller');
$this->processing();

$this->assing('fileList', $import->getFileList());
?>