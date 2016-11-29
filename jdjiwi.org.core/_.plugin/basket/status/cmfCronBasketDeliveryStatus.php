<?php


cLoader::library('cron/cmfCronUpdateDriver');
class cmfCronBasketDeliveryStatus extends cmfCronUpdateDriver {

	static public function update($id=0) {
		cmfCronRun::run();
        $sql = cRegister::sql();

        if($id) {
            $where = array('id'=>$id);
        } else {
            $where = array('status'=>array_keys(cmfOrder::getStatusList(1)));
        }
        $res = $sql->placeholder("SELECT * FROM ?t WHERE isDelivery='yes' AND `delete`='no' and ?w ORDER BY registerDate DESC", db_basket, $where)
			        ->fetchAssocAll();
		foreach($res as $basket) {
            switch($basket['deliveryType']) {
                case 'russian-post':
                    $cod = $basket['deliveryCod'];
                    if(empty($cod)) continue;
//                    pre($cod);

                    $post = array(
                        'ASP'=>'',
                        'BarCode'=>$cod,
                        'CA'=>'',
                        'CDAY'=>'14',
                        'CMONTH'=>'06',
                        'CYEAR'=>'2012',
                        'DFROM'=>'',
                        'DTO'=>'',
                        'FORUMID'=>'',
                        'NAVCURPAGE'=>'',
                        'NEWSID'=>'',
                        'OP'=>'',
                        'PARENTID'=>'',
                        'PATHCUR'=>'rp/servise/ru/home/postuslug/trackingpo',
                        'PATHFROM'=>'',
                        'PATHPAGE'=>'RP/INDEX/RU/Home/Search',
                        'PATHWEB'=>'RP/INDEX/RU/Home',
                        'SEARCHTEXT'=>'',
                        'WHEREONOK'=>'',
                        'WHEREONOK'=>'',
                        'search1'=>'',
                        'searchAdd'=>'',
                        'searchsign'=>'1',
                    );

//                    $context = stream_context_create(array(), $post);
//                    $content = file_get_contents('http://www.russianpost.ru/resp_engine.aspx?Path=rp/servise/ru/home/postuslug/trackingpo', false, $context);
                    $content = cFile::curl('http://www.russianpost.ru/resp_engine.aspx?Path=rp/servise/ru/home/postuslug/trackingpo', $post);

//                    pre($context, $content);
                    if(empty($content)) continue;
                    $content = strip_tags($content, '<h2>,<table>,<th>,<tr>,<td>,<a>,<br>');
                    preg_match('~.*Результат поиска\:(.*)$~iumsS', $content, $tmp);
                    $content = $tmp[1];
                    preg_match('~^(.*)<a href="#top" class="lnk_ontop">наверх</a>.*~iumsS', $content, $tmp);
                    $content = $tmp[1];

                    preg_match('~^(.*<table.*)(<table.*)$~iumsUS', $content, $tmp);

                    $data = array();
                    $data['content'] = $content;
//                    $data['start'] = $tmp[1];
//                    $data['end'] = $tmp[2];
//                    pre($data);

                    $sql->add(db_basket, array('deliveryDesc'=>cConvert::serialize($data)), $basket['id']);

                    break;
                default:
                    break;
            }

        }
//            exit;
		cmfCronRun::free();
	}
}

?>