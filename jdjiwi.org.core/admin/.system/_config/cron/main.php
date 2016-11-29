<?php

$list = $this->load('list', '_config_cron_list_controller');
$config = $this->load('config', '_config_cron_config_controller', 'cron');
$this->processing();
?>