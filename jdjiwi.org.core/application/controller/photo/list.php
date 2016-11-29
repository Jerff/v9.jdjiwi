<?php

$page = (int)cPages::param()->get(1);
if(!$page) $page = 1;

$limit = cSettings::get('photo', 'photoLimit');
$offset = ($page-1)*$limit;
if($offset>3000) return 404;
$res = cRegister::sql()->placeholder("SELECT SQL_CALC_FOUND_ROWS id, uri, header, image, image_title, notice, date FROM ?t WHERE visible='yes' ORDER BY date DESC LIMIT ?i, ?i", db_photo, $offset, $limit)
								->fetchAssocAll();
if(!$res) {
	return 404;
}
$_news = array();
foreach($res as $row) {
	$_news[] = array(	'date'=>date('d.m.Y', strtotime($row['date'])),
						'header'=>$row['header'],
						'title'=>cString::specialchars(empty($row['image_title']) ? $row['header'] : $row['image_title']),
                        'image'=>$row['image'] ? path_photo . $row['image'] : null,
                        'notice'=>nl2br($row['notice']),
						'url'=>cUrl::get('/photo/', $row['uri']));
}
$count = cRegister::sql()->getFoundRows();


$_page_url = cmfPagination::generate($page, $count, $limit, cSettings::get('photo', 'photoPage'),
    create_function('&$page, $k, $v', '
    	$page[$k]["url"] = $k==1 ? cmfGetUrl("/photo/all/") : cmfGetUrl("/photo/page/", array($k));'));
$this->assing('_news', $_news);
if($_page_url) $this->assing('_page_url', $_page_url);


cmfMenu::add('Фоторепортажи');

?>
