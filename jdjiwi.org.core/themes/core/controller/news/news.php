<?php

cGlobal::set('$headerId', 'news');
$uri = cPages::param()->get(1);
if(!$uri) return 404;
$news = cRegister::sql()->placeholder("SELECT id, date, header, content, title, keywords, description FROM ?t WHERE uri=? AND visible='yes'", db_news, $uri)
							->fetchAssoc();
if(!$news) {
	cGlobal::set('$menuId', 'news');
	return 'infoUrl';
}
$news['date'] = cmfView::date(strtotime($news['date']));
$this->assing('news', $news);

cmfMenu::add('Новости', cUrl::get('/news/all/'));
cmfMenu::add($news['header']);

cSeo::set('title', $news['title']);
cSeo::set('keywords', $news['keywords']);
cSeo::set('description', $news['description']);

?>
