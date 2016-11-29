<?php


class cmfCronUserActivateClear {

	static public function run() {
		cmfCronRun::run();
		$date = date('Y-m-d H:i:s');
		$res = cRegister::sql()->placeholder("SELECT GROUP_CONCAT(id) FROM ?t WHERE visible='no' AND register='no' AND DATE_ADD(registerDate, INTERVAL 5 DAY)<NOW()", db_user)
										->fetchRow(0);
        if($res) {
			cRegister::sql()->placeholder("DELETE FROM ?t WHERE id IN(?s)", db_user, $res);
			cRegister::sql()->placeholder("DELETE FROM ?t WHERE id IN(?s)", db_user_data, $res);
			cRegister::sql()->placeholder("DELETE FROM ?t WHERE user IN(?s)", db_user_adress, $res);
        }
		cmfCronRun::free();
	}

}

?>