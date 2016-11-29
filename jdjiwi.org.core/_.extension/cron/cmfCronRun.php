<?php


class cmfCronRun {

	const file = '.data/cron/cron.run';

	static private function getFile() {
		return cSoursePath . self::file;
	}

	static public function run() {
		if(cCommand::is('$isCron')) {
			file_put_contents(self::getFile(), time());
		}
	}

	static public function free() {
		if(cCommand::is('$isCron')) {
			if(file_exists(self::getFile())) {
				unlink(self::getFile());
			}
		}
	}

	static public function isRun() {
        if(file_exists(self::getFile())) {
			if((file_get_contents(self::getFile())+60*5)>time()) {
				return true;
			}
        }
        return false;
	}


	static public function runModul($name, $id=0) {
		if($id) cRegister::sql()->add(db_sys_cron, array('status'=>'start', 'date'=>date('Y-m-d H:i:s')), $id);
		self::run();
		cmfCronConfig::runModul($name);
		self::free();
		if($id) cRegister::sql()->add(db_sys_cron, array('status'=>'end', 'date'=>date('Y-m-d H:i:s')), $id);
		exit;
	}

}

?>