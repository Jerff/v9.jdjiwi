<?php


cLoader::library('cron/cmfCronUpdateDriver');
class cmfCronBackup extends cmfCronUpdateDriver {

	static public function run() {
		cmfCronRun::run();
        $sql = cRegister::sql();
        $res = $sql->placeholder("SELECT id, date, status, backup, time FROM ?t WHERE visible='yes'", db_backup_site)
        			->fetchAssocAll('id');
        foreach($res as $id=>$row) {
        	$time = strtotime($row['date']);
        	$isRun = false;
        	switch($row['time']) {
                case 'day':
    			$isRun = date('Y-d-Y', $time)!==date('Y-d-Y');
    			break;

                case 'week':
    			$isRun = date('Y-W', $time)!==date('Y-W');
    			break;
            }
        	if($isRun or $row['status']!=='end') {
        		cmfCronRun::run();
        		$sql->add(db_backup_site, array('status'=>'start', 'date'=>date('Y-m-d H:i:s')), $id);
        		self::export($id, $row['backup']);
        		$sql->add(db_backup_site, array('status'=>'end', 'date'=>date('Y-m-d H:i:s')), $id);
        		cmfCronRun::free();
        	}
        }
		cmfCronRun::free();
	}

	static protected function export($id, $backup) {
        $file = cmfBackupSite::getFile('['. $id .'] '. date('Y-m-d H.i.s') .'   '. $backup .'.gz');
        $backup = cConvert::pathToArray($backup);
        if(!$backup) return;

        $_tableAll = $_noDataAll = array();
        foreach($backup as $k) {
            cmfCronRun::run();
            list($_table, $_noData) = cmfBackupConfig::block($k);
            $_tableAll[$k] = $_table;
            $_noDataAll = array_merge($_noDataAll, $_noData);
        }
        cmfBackup::export($file, $_tableAll, $_noDataAll);
	}
}

?>