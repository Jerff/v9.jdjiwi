<?php

cGlobal::set('$headerId', 'photo');
$uri = cPages::param()->get(1);
if(!$uri) return 404;
$news = cRegister::sql()->placeholder("SELECT id, date, header, content, title, keywords, description FROM ?t WHERE uri=? AND visible='yes'", db_photo, $uri)
							->fetchAssoc();
if(!$news) {
	cGlobal::set('$menuId', 'photo');
	return 'infoUrl';
}
$news['date'] = cmfView::date(strtotime($news['date']));
$this->assing('news', $news);


/* изображения */
$res =  cRegister::sql()->placeholder("SELECT id, name, image_main AS main, image_section AS section FROM ?t WHERE photo=? AND visible='yes' AND image IS NOT NULL ORDER BY pos DESC", db_photo_image, $news['id'])
                             ->fetchAssocAll();
$_image = array();
foreach($res as $v) {
    $_image[$v['id']] = array('title'=>cString::specialchars($v['name'] ? $v['name'] : $news['header']),
                              'main'=>cBaseImgUrl . path_photo . $v['main'],
                              'section'=>cBaseImgUrl . path_photo . $v['section']);
}
if($_image) {
    $this->assing('_image', $_image);
}

cmfMenu::add('Фоторепортажи', cUrl::get('/photo/all/'));
cmfMenu::add($news['header']);

cmfSeo::set('title', $news['title']);
cmfSeo::set('keywords', $news['keywords']);
cmfSeo::set('description', $news['description']);

cmfHeader::setJs('/sourseJs/ad-gallery/jquery.ad-gallery.js');
cmfHeader::setCss('/sourseJs/ad-gallery/jquery.ad-gallery.css');

?>
