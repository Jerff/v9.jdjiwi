<?php



$list = $this->load('list', 'subscribe_history_list_controller');
if(cAdmin::menu()->sub()->getId()) {
    cAdmin::menu()->sub()->type('param');
    if(!$row = cLoader::initModul('subscribe_edit_db')->getDataRecord(cAdmin::menu()->sub()->getId())) {
        $this->command()->noRecord();
    }
    if($row['type']!=='user') {
        $this->command()->noRecord();
    }
} else {
    $this->command()->noRecord();
}
$this->processing();



cAdmin::menu()->sub()->type('history');

?>
