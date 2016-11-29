<?php

if (0) {
    $sql = cRegister::sql();
    $mail = $sql->placeholder("SELECT `email`, `type` FROM ?t", db_subscribe_mail)
            ->fetchRowAll(0, 1, 1);

    $list = array();
    foreach (model_subscribe::typeList() as $k => $v) {
        $list[$v['name']] = $k;
    }

    $res = $sql->placeholder("SELECT id, user, data FROM ?t WHERE `delete`='no'", db_basket)
            ->fetchAssocAll('id');
    foreach ($res as $id => $row) {
        list(, $email,,, $subcribe) = cConvert::unserialize($row['data']);
        if (is_array($subcribe)) {
            foreach ($subcribe as $k => $v)
                if (isset($list[$k])) {
                    $k = $list[$k];
                    if (!isset($mail[$email][$k])) {
                        cmfSubscribe::addUser($row['user'], $email, $k);
                    }
                }
        }
//        pre($email, $subcribe, $list);
    }

//    pre($mail);

    exit;
}



$list = $this->load('list', 'subscribe_list_controller');
$this->assing('filterType', $list->filterType());
$config = $this->load('config', 'subscribe_config_controller', 'subscribe');
$this->processing();
?>