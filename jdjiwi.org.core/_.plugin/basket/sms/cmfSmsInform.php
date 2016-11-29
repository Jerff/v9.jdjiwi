<?php


cLoader::library('basket/sms/cmfSmsInformConfig');
cLoader::library('limit/cmfLimit');
class cmfSmsInform {

    private $driver = null;
	private $config = null;
    private $status = null;

	public function __construct() {
		$this->config = cSettings::get('sms');
        $this->driver = cmfSmsInformConfig::getDriver($this->config['api']);

        $res = cRegister::sql()->placeholder("SELECT status, notice FROM ?t WHERE visible='yes'", db_sms_inform)
                                    ->fetchRowAll(0, 1);
        foreach($res as $k=>$v) {
            if($id = (int)$k) {
                $this->status[str_replace($id, '', $k)][$id] = $v;
            } else {
                $this->status[$k] = $v;
            }
        }
	}

    public function sendMessage($send, $type, $status=null) {
        if(!$this->driver
           or $this->config['visible']==='no'
           or !isset($this->status[$type])
           or empty($send['phone'])
           or ($status and !isset($this->status[$type][$status]))) return;
        $message = $status ? $this->status[$type][$status] : $this->status[$type];
        foreach($send as $k=>$v) {
            $message = str_replace('{'. $k .'}', $v, $message);
        }
        $this->driver->send($this->config, $send['phone'], $message);
	}

    public function send($phone, $message, $param=array()) {
        if(!$this->driver
           or $this->config['visible']==='no') return;

        foreach(cmfMail::getMailVar() as $k=>$v) {
            $message = str_replace('{'. $k .'}', $v, $message);
        }
        foreach($param as $k=>$v) {
            $message = str_replace('{'. $k .'}', $v, $message);
        }
//        pre($this->config, $phone, $message);
        $this->driver->send($this->config, $phone, $message);
	}

    static public function newOrder($send) {
        if(cmfLimit::is('sms', cSettings::get('sms', 'smsLimit'))) {
            cmfLimit::add('sms');
            $sms = new cmfSmsInform();
            $sms->sendMessage($send, 'newOrder');
        }
	}

    static public function changeStatus($id, $send) {
        $sms = new cmfSmsInform();
        $sms->sendMessage($send, 'status', $id);
	}

}

?>