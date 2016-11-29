<?php

cGlobal::set('$headerId', 'article');
$uri = cPages::param()->get(1);
if(!$uri) return 404;
$news = cRegister::sql()->placeholder("SELECT id, date, header, content, title, keywords, description FROM ?t WHERE uri=? AND visible='yes'", db_article, $uri)
							->fetchAssoc();
if(!$news) {
	cGlobal::set('$menuId', 'article');
	return 'infoUrl';
}
$news['date'] = cmfView::date(strtotime($news['date']));
$this->assing('news', $news);


/* attach */
$res = cRegister::sql()->placeholder("SELECT  p.id, p.name, u.url, p.price, p.image_small AS image FROM ?t p LEFT JOIN ?t u ON(u.product=p.id) WHERE u.product=p.id AND p.id IN(SELECT product2 FROM ?t WHERE article=?) AND u.brand='0' AND p.count>'0' ORDER BY name", db_product, db_product_url, db_article_attach, $news['id'])
                ->fetchAssocAll();
$attach = array();
foreach($res as $row) {
    $attach[$row['id']] = array('title'=>cString::specialchars($row['name']),
                                'image'=>cBaseImgUrl . path_product . $row['image'],
                                'url'=>cUrl::get('/product/', $row['url']));
}
if($attach) {
    $this->assing('attach', $attach);
}


cmfMenu::add('Статьи', cUrl::get('/article/all/'));
cmfMenu::add($news['header']);

cSeo::set('title', $news['title']);
cSeo::set('keywords', $news['keywords']);
cSeo::set('description', $news['description']);

?>
