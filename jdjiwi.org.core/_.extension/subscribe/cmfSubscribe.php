<?php


class cmfSubscribe {

    static public function typeList() {
        return array(
            'news'=>'О новых товарах',
            'sales'=>'Об акциях и распродажах');
	}

    static public function selectForm(&$form, $id, $email) {
        $is = cRegister::sql()->placeholder("SELECT 1 FROM ?t WHERE user IN(0, ?) AND email=? AND `type`=''", db_subscribe_mail, $id, $email)
                                   ->numRows();
        if($is) {
            foreach(cmfSubscribe::typeList() as $k=>$v) {
                $form->select($k, 'yes');
            }
        } else {
            $res = cRegister::sql()->placeholder("SELECT `type` FROM ?t WHERE user IN(0, ?) AND email=?", db_subscribe_mail, $id, $email)
                                        ->fetchAssocAll();
            foreach($res as $row) {
                $form->select($row['type'], 'yes');
            }
        }
    }

    public static function addUser($user, $email, $type) {
        if(cRegister::sql()->placeholder("SELECT 1 FROM ?t WHERE email=? AND `type`=?", db_subscribe_mail, $email, $type)
            ->numRows()) return;
        if($user) {
            cRegister::sql()->placeholder("UPDATE ?t SET user=? WHERE user='0' AND email=?", db_subscribe_mail, $user, $email);
            cRegister::sql()->add(db_subscribe_mail, array('user'=>$user, 'email'=>$email, 'created'=>date('Y-m-d H:i:s'), 'type'=>$type, 'visible'=>'yes', 'subscribe'=>'yes'),
                                                          array('user'=>$user, 'AND', 'type'=>$type));
            cRegister::sql()->del(db_subscribe_mail, array('user'=>$user, 'AND', 'type'=>''));

        } else {
            cRegister::sql()->add(db_subscribe_mail, array('user'=>$user, 'email'=>$email, 'created'=>date('Y-m-d H:i:s'), 'type'=>$type, 'visible'=>'yes', 'subscribe'=>'yes'),
                                                          array('email'=>$email, 'AND', 'type'=>$type));
            cRegister::sql()->del(db_subscribe_mail, array('email'=>$email, 'AND', 'type'=>''));
        }
    }

    public static function delUser($user, $email, $type) {
        if($user)
        cRegister::sql()->placeholder("UPDATE ?t SET user=? WHERE user='0' AND email=?", db_subscribe_mail, $user, $email);
        cRegister::sql()->del(db_subscribe_mail, array('user'=>$user, 'AND', 'type'=>array($type, '')));
    }

}

?>