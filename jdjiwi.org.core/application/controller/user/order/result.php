<?php


$modul = cPages::param()->get(1);
$sql = cRegister::sql();
$pay = $sql->placeholder("SELECT id, data, name, commission FROM ?t WHERE modul=? AND visible='yes'", db_payment, $modul)
			 ->fetchAssoc();
if(!$pay) {
    return 404;
}
$data = cConvert::unserialize($pay['data']);
$data['commission'] = $pay['commission'];

cLoader::library('payment/cmfPayment');
cmfPayment::result($modul, $data);

?>