<?php


class cmfPDOStatement extends PDOStatement {

	//освобождение памяти при уничтожение объекта (сам он это не делает)
	public function __destruct() {
		$this->closeCursor();
	}

	public function fetchAssoc() {
		return $this->fetch(PDO::FETCH_ASSOC);
	}

	public function fetchRow($id=null) {
		$r = $this->fetch(PDO::FETCH_NUM);
		return is_int($id) ? $r[$id] : $r;
	}

	public function free() {
		$this->closeCursor();
	}

	public function numRows() {
		return $this->rowCount();
	}


	public function &fetchAssocAll() {
        $res = $this->fetchAll(PDO::FETCH_ASSOC);
        if(!$res or !func_num_args()) return $res;
		$new = array();
		switch(func_num_args()) {
			case 1:
				$arg = func_get_arg(0);
				while(list(, $row)=each($res)) {
					$id = $row[$arg];
					unset($row[$arg]);
					$new[$id] = $row;
				}
				break;

			case 2:
				list($k1, $k2) = func_get_args();
				while(list(, $row)=each($res)) {
					$id1 = $row[$k1];
					$id2 = $row[$k2];
					//unset($row[$k1], $row[$k2]);
					$new[$id1][$id2] = $row;
				}
				break;

			case 3:
				list($k1, $k2, $v) = func_get_args();
				while(list(, $row)=each($res)) {
					$new[$row[$k1]][$row[$k2]] = $row[$v];
				}
				break;
		}
		return $new;
	}

	public function fetchRowAll() {
        $res = $this->fetchAll(PDO::FETCH_NUM);
        if(!$res or !func_num_args()) return $res;
		$new = array();
		switch(func_num_args()) {
			case 1:
				list($k) = func_get_args();
				while(list(, $row)=each($res)) {
					$new[$row[$k]] = $row[$k];
				}
				break;

			case 2:
				list($k, $v) = func_get_args();
				while(list(, $row)=each($res)) {
					$new[$row[$k]] = $row[$v];
				}
				break;

			case 3:
				list($k1, $k2, $v) = func_get_args();
				while(list(, $row)=each($res)) {
					$new[$row[$k1]][$row[$k2]] = $row[$v];
				}
				break;
		}
		return $new;
	}

}

?>
