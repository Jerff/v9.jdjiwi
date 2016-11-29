<?php

$list = $this->load('list', '_administrator_config_controller');
$this->assing('filterGroup', $list->filterGroup());
$this->processing();
?>