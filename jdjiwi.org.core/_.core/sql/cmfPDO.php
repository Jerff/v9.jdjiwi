<?php

cLoader::library('sql/cmfPDOStatement');
class cmfPDO extends PDO {

	function __construct($dsn, $user=null, $password=null) {
		try {
			parent::__construct($dsn, $user, $password);
			$this->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('cmfPDOStatement'));
			$this->query("SET NAMES utf8 COLLATE utf8_unicode_ci");
		} catch (PDOException $e) {
			print "Error!: база данных недоступна";
            cLog::errorLog($e);
			exit();
		}
	}

}

?>
