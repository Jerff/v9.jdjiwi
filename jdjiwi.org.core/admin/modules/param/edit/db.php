<?php


class param_edit_db extends driver_db_edit {

	protected function getTable() {
		return db_param;
	}

	public function updateData($list, $send) {
		cmfUpdateCache::update('param');
	}


	private $sortType = null;
	public function paramSort($param, &$data) {
        $this->sortType = $this->getFeildRecord('sort', $param);
        uasort($data, array($this, '_paramSort'));
	}
	protected function _paramSort($a, $b) {
		$a = str_replace(array(' ', ','), array('', '.'), $a);
		$b = str_replace(array(' ', ','), array('', '.'), $b);

		preg_match_all('~([0-9.]+|[a-zа-я]+)~i', $a, $tmpA);
		$tmpA = $tmpA[1];

		preg_match_all('~([0-9.]+|[a-zа-я]+)~i', $b, $tmpB);
		$tmpB = $tmpB[1];

        $c = count($tmpA);
		$b = count($tmpB);
		if($c<$b)  $c = $b;
		for($i=0; $i<$c; $i++) {
            $a = get($tmpA, $i);
            $b = get($tmpB, $i);
            if(is_null($a)) return 1;
            if(is_null($b)) return -1;
            if($a!==$b) {
            	switch($this->sortType) {
                    case 'size';
                        $a = cString::strtolower($a);
                        $b = cString::strtolower($b);
                        if(preg_match('~(s|m|l|x)~S', $a) and preg_match('~(s|m|l|x)~S', $a)) {
                            $size = array(); $i = 0;
                            foreach(array('xxs', 'xs', 's', 'm', 'l', 'xl', 'xxl', 'xxxl') as $v) {
                                $size[$v] = ++$i;
                            }
                            if(isset($size[$a]) and isset($size[$b])) {
                                return ($size[$a] < $size[$b]) ? -1 : 1;
                            }

                        }

                        $c2 = strlen($a);
                		$b2 = strlen($b);
                		if($c2<$b2) $c2 = $b2;
                        for($j=0; $j<$c2; $j++) {
                            $a2 = $a{$j};
                            $b2 = $b{$j};
                            if($a2!==$b2) {
                                $ai = stripos(' smlx', $a2);
                                $bi = stripos(' smlx', $b2);
                                if($ai and $bi) {
                                    return ($ai < $bi) ? -1 : 1;
                                }
                                return ($a2 < $b2) ? -1 : 1;
                            }
                        }
                        break;
                }
                return ($a < $b) ? -1 : 1;
            }
		}
		return 0;
	}


	protected function paramGet($param) {
		$data = $this->getDataRecord($param);
		if(!empty($data['value'])) {
            return unserialize($data['value']);
		} else {
			return array();
		}
	}
	protected function paramSet($param, $value) {
		$this->saveId($param, array('value'=>serialize($value)));
	}



	public function paramUpdate($param, $id, $value) {
		if(!empty($value)) {
            $data = $this->paramGet($param);
            if(isset($data[$id])) {
	            $data[$id] = $value;
                $this->paramSort($param, $data);
	            $this->paramSet($param, $data);
	            cGlobal::set('$paramLog', 'Параметр обновлен');
	            return $data;
            } else {
            	cGlobal::set('$paramLog', 'Сначало необходимо добавить параметр');
            }
		} else {
			cGlobal::set('$paramLog', 'Поле не заполнено');
		}
		return false;
	}


	public function paramAdd($param, $value) {
		$value = trim($value);
		if(empty($value)) {
			cGlobal::set('$paramLog', 'Поле не заполнено');
			return false;
		}

		$data = $this->paramGet($param);
		if(array_search($value, $data)) {
			cGlobal::set('$paramLog', 'Параметр уже существует');
			return false;
		}

		$is = cConvert::translate(str_replace(array(' ', "\t", "\n", '-', '+', '_', '.', ','), '', $value));
		foreach($data as $k=>$v) {
			$v = cConvert::translate(str_replace(array(' ', "\t", "\n", '-', '+', '_', '.', ','), '', $v));
			if($is===$v) {
				cGlobal::set('$paramLog', 'Параметр уже существует');
				return false;
			}
		}

		if($data) {
    		array_push($data, $value);
            $this->paramSort($param, $data);
		} else {
			$data[1] = $value;
		}
		$this->paramSet($param, $data);
		cGlobal::set('$paramLog', 'Параметр добавлен');
		return array_search($value, $data);
	}


	public function paramDelete($param, $id) {
		$data = $this->paramGet($param);
		if(isset($data[$id])) {
            unset($data[$id]);
            $this->paramSet($param, $data);
            cGlobal::set('$paramLog', 'Параметр удален');

            //$this->getSql()->del(db_param_separator, array('param'=>$param));
            //$this->getSql()->del(db_param_checkbox, array('param'=>$param, 'AND', 'value'=>$id));
            //$this->getSql()->del(db_param_select, array('param'=>$param, 'AND', 'value'=>$id));

            return $data ? key($data) : false;
		} else {
            cGlobal::set('$paramLog', 'Параметр не выбран');
            return null;
		}
	}

}

?>