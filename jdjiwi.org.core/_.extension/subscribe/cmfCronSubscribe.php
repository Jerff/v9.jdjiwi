<?php


cLoader::library('cron/cmfCronUpdateDriver');
class cmfCronSubscribe extends cmfCronUpdateDriver {

	static public function run() {
		cmfCronRun::run();
        $sql = cRegister::sql();

        $mType = cmfSubscribe::typeList();

        $cmfMail = new cmfMail();
        $count = cSettings::get('subscribe', 'mailMax');
        $res = $sql->placeholder("SELECT id, type, email, emailSend, header, content FROM ?t WHERE status='активна' AND visible='yes' AND dateStart<=NOW() ORDER BY dateStart DESC", db_subscribe)
			        ->fetchAssocAll();
		foreach($res as $subscribe) {
            $id = $subscribe['id'];
            $header = $subscribe['header'];
	        $content = $subscribe['content'];

            if($subscribe['type']==='user') {
                if(empty($subscribe['email'])) {
                    $sql->add(db_subscribe, array('status'=>'закончена'), $id);
                    continue;
                }

                // рассылка по адресам
                $hash = sha1($subscribe['email'] . $subscribe['header'] . $subscribe['content']);
                $save = array(//'subscribe'=>$id,
                              //'hash'=>$hash,
                              'header'=>$subscribe['header'],
                              'content'=>$subscribe['content'],
                              'email'=>$subscribe['emailSend'],
                              'date'=>date('Y-m-d H:i:s'));
                $mEmail = array_map('trim', explode(',', $subscribe['email']));
                $mSend = array_map('trim', explode(',', $subscribe['emailSend']));
                $mSend = array_combine($mSend, $mSend);
                foreach($mEmail as $email) if(!isset($mSend[$email])) {
                    cmfCronRun::run();
                    $cmfMail->sendHTML($email, $header, $content);
                    $save['email'] .= (empty($save['email']) ? '' : ', ') . $email;
                    $sql->add(db_subscribe_history, $save, array('hash'=>$hash, 'AND', 'subscribe'=>$id));
                    $sql->add(db_subscribe, array('emailSend'=>$save['email']), $id);
                    if($count--) continue;
                }
                if($count) {
                    $sql->add(db_subscribe, array('email'=>'', 'emailSend'=>''), $id);
                }
            } else {
                // обычная рассылка
                $where = array();
                if($subscribe['type']==='all') {
                    $where['type'] = array_keys($mType);
                } elseif(isset($mType[$subscribe['type']])) {
                    $where['type'] = $subscribe['type'];
                } else {
                    continue;
                }
                $res2 = $sql->placeholder("SELECT id, email FROM ?t WHERE email NOT IN(SELECT email FROM ?t WHERE id IN(SELECT mail FROM ?t WHERE subscribe=?)) AND ?w AND subscribe='yes' AND visible='yes' GROUP BY email", db_subscribe_mail,  db_subscribe_mail, db_subscribe_status, $id, $where)
                            ->fetchAssocAll();
                foreach($res2 as $row) {
                    cmfCronRun::run();
                    $cmfMail->sendHTML($row['email'], $header, $content);
                    $sql->add(db_subscribe_status, array('mail'=>$row['id'], 'subscribe'=>$id));
                    if($count--) continue;
                }
            }

            if($count) {
                $sql->add(db_subscribe, array('status'=>'закончена'), $id);
            }
        }
		cmfCronRun::free();
	}
}

?>