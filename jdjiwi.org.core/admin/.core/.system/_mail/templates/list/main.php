<?php



$list = $this->load('list', '_mail_templates_list_controller');
$this->assing('filterSection', $list->filterSection());
$this->processing();





?>
