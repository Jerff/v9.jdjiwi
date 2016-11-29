<?php


abstract class driver_db_edit_param extends driver_db_edit {

	abstract public function paramList();


    public function deleteProduct($id) {
		$this->sql()->del(db_param_select, array('id'=>$id));
		$this->sql()->del(db_param_checkbox, array('id'=>$id));
	}

    public function save($send) {
		list($send, $price, $dump) = $send;
		$min = empty($price) ? 0 : min($price);
		return parent::save(array('param'=>serialize($send), 'paramPrice'=>serialize($price), 'paramDump'=>serialize($dump), 'price2'=>$min, 'count'=>array_sum($dump)));
	}

    public function getKeyCheckbox($param, $value, $sep='_') {
		$param = explode($sep, $param);
        list($param, $key) = $param;
        return array($param, $key, $value);
	}

	public function saveElement($param, $value) {
		static $_param = null; static $data = null; static $price = null; static $dump = null;
		if(!$_param) {
		    $_param = $this->paramList();

		    list($data, $price, $dump) = $this->sql()->placeholder("SELECT param, paramPrice, paramDump, count FROM ?t WHERE id=?", $this->getTable(), $this->id()->get())
		                            ->fetchRow();
            $data = cConvert::unserialize($data);
            $price = cConvert::unserialize($price);
            $dump = cConvert::unserialize($dump);
		}
		list($id) = explode('_', $param);
		switch($type=get2($_param, $id, 'type')) {
			case 'select':
			case 'radio':
				if($value<0 or is_null($value)) {
					unset($data[$param]);
					$this->sql()->del(db_param_select, array('id'=>$this->id()->get(), 'AND', 'param'=>$param));
				} else {
					$data[$param] = $value;
					$this->sql()->replace(db_param_select, array('id'=>$this->id()->get(), 'param'=>$param, 'value'=>$value));
				}
				break;

			case 'text':
			case 'textarea':
				if(empty($value) or is_null($value)) {
                    unset($data[$param]);
				} else {
					$data[$param] = $value;
				}
				break;

			case 'checkbox':
				list($param, $key, $value) = $this->getKeyCheckbox($param, $value);
                if(!isset($data[$param]) or !is_array($data[$param])) $data[$param] = array();
				if($value==='yes') {
					$data[$param][$key] = $key;
					$this->sql()->replace(db_param_checkbox, array('id'=>$this->id()->get(), 'param'=>$param, 'value'=>$key));
				} else {
					unset($data[$param][$key]);
					$this->sql()->del(db_param_checkbox, array('id'=>$this->id()->get(), 'AND', 'param'=>$param, 'AND', 'value'=>$key));
				}
				break;

			case 'basket':
				if(strpos($param, '_key_')) {
                    list($param, $key, $value) = $this->getKeyCheckbox($param, $value, '_key_');
    				if($value==='yes') {
    					if(!isset($data[$param]) or !is_array($data[$param])) $data[$param] = array();
				        $data[$param][$key] = $key;
    					$this->sql()->replace(db_param_checkbox, array('id'=>$this->id()->get(), 'param'=>$param, 'value'=>$key));
    				} else {
    					unset($data[$param][$key]);
    					$this->sql()->del(db_param_checkbox, array('id'=>$this->id()->get(), 'AND', 'param'=>$param, 'AND', 'value'=>$key));
    				}
				} elseif(strpos($param, '_price_')) {
                    list($param, $key, $value) = $this->getKeyCheckbox($param, $value, '_price_');
				    if(!is_array($price)) $price = array();
				    $price[$key] = $value;
				} elseif(strpos($param, '_dump_')) {
				    list($param, $key, $value) = $this->getKeyCheckbox($param, $value, '_dump_');
				    if(!is_array($dump)) $dump = array();
				    $dump[$key] = $value;
		        }
		        foreach($price as $k=>$v) {
		            if(!isset($data[$param][$k])) {
		                unset($price[$k]);
		            }
		        }
		        foreach($dump as $k=>$v) {
		            if(!isset($data[$param][$k])) {
		                unset($dump[$k]);
		            }
		        }
				break;

			case 'separator':
				if($value==='yes') {
					$data[$param] = true;
				} else {
					unset($data[$param]);
				}
				break;

			default:
			    return;
		}


		$this->save(array($data, $price, $dump));
		$this->update()->set($this->id()->get(), $param);
	}

}

?>