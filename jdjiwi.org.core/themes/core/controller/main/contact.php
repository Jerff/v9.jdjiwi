<?php

$info = cRegister::sql()->placeholder("SELECT * FROM ?t WHERE id='contact'", db_main)
							->fetchAssoc();
if(!$info) return 404;
$this->assing('info', $info);

cSeo::set('title', $info['title']);
cSeo::set('keywords', $info['keywords']);
cSeo::set('description', $info['description']);

cmfMenu::add('Контакты');
cGlobal::set('$catalogId', 'contact');

$office = cRegister::sql()->placeholder("SELECT * FROM ?t WHERE id='contact/main'", db_main)
							->fetchAssoc();
$mapYandexApi = explode("\n", str_replace("\r", '', $office['mapYandexApi']));
if(false!==($res=array_search(cUrl::get('/index/'), $mapYandexApi))) {
	if(isset($mapYandexApi[$res+1])) {
		cHeader::setJsSourse('http://api-maps.yandex.ru/1.1/index.xml?key='. $mapYandexApi[$res+1]);
		$this->assing2('office', $office);
    }
}

?>