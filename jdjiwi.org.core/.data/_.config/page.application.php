<?php

$p = array();
// --------- служебные ---------
$p['/404/'] = array(
't' => 0,
'b' => 'application',
'p' => 'system/404',
'noUrl' => true
);

$p['/header/'] = array(
'b' => 'application',
'p' => 'system/header',
'isMain' => true
);

$p['/footer/'] = array(
'b' => 'application',
'p' => 'system/footer',
'isMain' => true,
'noUrl' => true
);

$p['/index/footer/'] = array(
'b' => 'application',
'p' => 'system/index.footer',
'noUrl' => true
);

$p['/info/header/'] = array(
'b' => 'application',
'p' => 'system/info.header',
'isMain' => true
);

$p['/info/footer/'] = array(
'b' => 'application',
'p' => 'system/info.footer',
'noUrl' => true
);

$p['/catalog/header/'] = array(
'b' => 'application',
'p' => 'system/catalog.header',
'isMain' => true
);

$p['/catalog/footer/'] = array(
'b' => 'application',
'p' => 'system/catalog.footer'
);

$p['/order/header/'] = array(
'b' => 'application',
'p' => 'system/order.header',
'isMain' => true
);
// --------- /служебные ---------
// --------- Главная ---------
$p['/index/'] = array(
't' => 1,
'b' => 'application',
'u' => '/',
'p' => 'main/index',
'noUrl' => true
);

$p['/info/'] = array(
't' => 0,
'b' => 'application',
'u' => '/(1)/',
'p' => 'main/info'
);

$p['/info/fancybox/'] = array(
'b' => 'application',
'p' => 'main/info.fancybox'
);

$p['/content/'] = array(
't' => 0,
'b' => 'application',
'u' => '/(1)/',
'p' => 'main/content'
);

$p['/call-back/'] = array(
't' => 0,
'b' => 'application',
'u' => '/call-back/',
'p' => 'main/call-back',
'noUrl' => true
);

$p['/call-back/fancybox/'] = array(
'b' => 'application',
'p' => 'main/call-back.fancybox',
'noUrl' => true
);
// --------- /Главная ---------
// --------- Новости ---------
$p['/news/all/'] = array(
't' => 0,
'b' => 'application',
'u' => '/{path=news}/',
'p' => 'news/list'
);

$p['/news/page/'] = array(
't' => 0,
'b' => 'application',
'u' => '/{path=news}/page_(1)/',
'p' => 'news/list'
);

$p['/news/year/all/'] = array(
't' => 0,
'b' => 'application',
'u' => '/{path=news}/year/',
'p' => 'news/year'
);

$p['/news/year/all/page/'] = array(
't' => 0,
'b' => 'application',
'u' => '/{path=news}/year/page_(1)/',
'p' => 'news/year'
);

$p['/news/year/'] = array(
't' => 0,
'b' => 'application',
'u' => '/{path=news}/year/(1)/',
'p' => 'news/year'
);

$p['/news/year/page/'] = array(
't' => 0,
'b' => 'application',
'u' => '/{path=news}/year/(1)/page_(2)/',
'p' => 'news/year'
);

$p['/news/item/'] = array(
't' => 0,
'b' => 'application',
'u' => '/{path=news}/(1)/',
'p' => 'news/news'
);
// --------- /Новости ---------
// --------- Личный кабинет ---------
$p['/user/register/'] = array(
't' => 4,
'b' => 'application',
'u' => '/user/register/',
'p' => 'user/register',
'noUrl' => true
);

$p['/user/register/fancybox/'] = array(
'b' => 'application',
'p' => 'user/register.fancybox',
'noUrl' => true
);

$p['/user/enter/'] = array(
't' => 4,
'b' => 'application',
'u' => '/user/enter/',
'p' => 'user/enter',
'noUrl' => true
);

$p['/user/enter/fancybox/'] = array(
'b' => 'application',
'p' => 'user/enter.fancybox',
'noUrl' => true
);

$p['/user/'] = array(
't' => 4,
'b' => 'application',
'u' => '/user/',
'p' => 'user/user',
'brousers' => false,
'!cache' => true,
'noUrl' => true
);

$p['/user/page/'] = array(
't' => 4,
'b' => 'application',
'u' => '/user/page_(1)/',
'p' => 'user/user',
'brousers' => false,
'!cache' => true,
'noUrl' => true
);

$p['/user/info/'] = array(
't' => 4,
'b' => 'application',
'u' => '/user/info/',
'p' => 'user/info',
'brousers' => false,
'!cache' => true,
'noUrl' => true
);

$p['/user/info/password/'] = array(
't' => 4,
'b' => 'application',
'u' => '/user/info/password/',
'p' => 'user/info.password',
'brousers' => false,
'!cache' => true,
'noUrl' => true
);

$p['/user/info/subscribe/'] = array(
't' => 4,
'b' => 'application',
'u' => '/user/info/subscribe/',
'p' => 'user/info.subscribe',
'brousers' => false,
'!cache' => true,
'noUrl' => true
);

$p['/user/exit/'] = array(
't' => 4,
'b' => 'application',
'u' => '/user/exit/',
'p' => 'user/exit',
'brousers' => false,
'!cache' => true,
'noUrl' => true
);

$p['/user/command/'] = array(
't' => 4,
'b' => 'application',
'u' => '/user/command/(1)/',
'p' => 'user/command'
);

$p['/user/password/'] = array(
't' => 4,
'b' => 'application',
'u' => '/user/password/',
'p' => 'user/password',
'noUrl' => true
);


// --------- Заказы ---------
$p['/user/order/'] = array(
't' => 4,
'b' => 'application',
'u' => '/user/order/',
'p' => 'user/order/list',
'brousers' => false,
'!cache' => true,
'noUrl' => true
);

$p['/user/order/page/'] = array(
't' => 4,
'b' => 'application',
'u' => '/user/order/page_(1)/',
'p' => 'user/order/list',
'brousers' => false,
'!cache' => true,
'noUrl' => true
);

$p['/user/order/one/'] = array(
't' => 5,
'b' => 'application',
'u' => '/user/order/(1)/',
'p' => 'user/order/order',
'brousers' => false,
'!cache' => true,
'noUrl' => true
);

$p['/user/order/pay/'] = array(
't' => 4,
'b' => 'application',
'u' => '/user/order/(1)/pay/',
'p' => 'user/order/pay',
'brousers' => false,
'!cache' => true,
'noUrl' => true
);

$p['/user/order/pay/run/'] = array(
't' => 6,
'b' => 'application',
'u' => '/user/order/(1)/(2)/',
'p' => 'user/order/pay_run',
'brousers' => false,
'!cache' => true,
'noUrl' => true
);

$p['/user/order/pay/send/'] = array(
't' => 6,
'b' => 'application',
'u' => '/user/order/(1)/(2)/send/',
'p' => 'user/order/pay_run',
'brousers' => false,
'!cache' => true,
'noUrl' => true
);

$p['/user/order/result/'] = array(
'b' => 'application',
'u' => '/user/order/(1)/result/',
'p' => 'user/order/result',
'brousers' => false,
'!cache' => true,
'noUrl' => true
);

$p['/user/order/success/'] = array(
'b' => 'application',
'u' => '/user/order/(1)/success/',
'p' => 'user/order/success',
'brousers' => false,
'!cache' => true,
'noUrl' => true
);

$p['/user/order/fail/'] = array(
'b' => 'application',
'u' => '/user/order/(1)/fail/',
'p' => 'user/order/fail',
'brousers' => false,
'!cache' => true,
'noUrl' => true
);
// --------- /Заказы ---------
// --------- /Личный кабинет ---------
// --------- Поиск ---------
$p['/search/'] = array(
't' => 2,
'b' => 'application',
'u' => '/{path=search}/',
'p' => 'product/section',
'noUrl' => true
);

$p['/section/search/'] = array(
't' => 2,
'b' => 'application',
'u' => '/{path=search}/(1)/',
'p' => 'product/section'
);
// --------- /Поиск ---------
// --------- Карзина заказов ---------
$p['/basket/'] = array(
't' => 3,
'b' => 'application',
'u' => '/basket/',
'p' => 'basket/basket',
'brousers' => false,
'!cache' => true,
'noUrl' => true
);

$p['/basket/delivery/'] = array(
't' => 3,
'b' => 'application',
'u' => '/basket/delivery/',
'p' => 'basket/delivery',
'brousers' => false,
'!cache' => true,
'noUrl' => true
);

$p['/basket/adress/'] = array(
't' => 3,
'b' => 'application',
'u' => '/basket/adress/',
'p' => 'basket/adress',
'brousers' => false,
'!cache' => true,
'noUrl' => true
);

$p['/basket/subscribe/'] = array(
't' => 3,
'b' => 'application',
'u' => '/basket/subscribe/',
'p' => 'basket/subscribe',
'brousers' => false,
'!cache' => true,
'noUrl' => true
);

$p['/basket/pay/'] = array(
't' => 3,
'b' => 'application',
'u' => '/basket/pay/',
'p' => 'basket/pay',
'brousers' => false,
'!cache' => true,
'noUrl' => true
);

$p['/basket/confirmation/'] = array(
't' => 3,
'b' => 'application',
'u' => '/basket/confirmation/',
'p' => 'basket/confirmation',
'brousers' => false,
'!cache' => true,
'noUrl' => true
);

$p['/basket/ok/'] = array(
't' => 3,
'b' => 'application',
'u' => '/basket/ok/',
'p' => 'basket/basket_ok',
'noUrl' => true
);

$p['/basket/none/'] = array(
't' => 0,
'b' => 'application',
'p' => 'basket/none',
'brousers' => false,
'!cache' => true,
'noUrl' => true
);
// --------- /Карзина заказов ---------
// --------- Рассылка ---------
$p['/subscribe/'] = array(
't' => 0,
'b' => 'application',
'u' => '/subscribe/',
'p' => 'subscribe/subscribe',
'brousers' => false,
'noUrl' => true
);

$p['/subscribe/command/'] = array(
't' => 0,
'b' => 'application',
'u' => '/subscribe/command/(1)/',
'p' => 'subscribe/command'
);
// --------- /Рассылка ---------
// --------- Размеры ---------
$p['/info/size/'] = array(
't' => 0,
'b' => 'application',
'u' => '/info/size/',
'p' => 'size/info',
'noUrl' => true
);

$p['/info/size/fancybox/'] = array(
'b' => 'application',
'p' => 'size/info.fancybox',
'noUrl' => true
);
// --------- /Размеры ---------
// --------- Товары ---------
$p['/catalog/url/'] = array(
't' => 2,
'b' => 'application',
'p' => 'product/catalog_url',
'brousers' => false,
'!cache' => true,
'noUrl' => true
);

$p['/catalog/'] = array(
't' => 2,
'b' => 'application',
'u' => '/(1)/',
'p' => 'product/section'
);

$p['/section/main/'] = array(
't' => 2,
'b' => 'application',
'u' => '/(1)/',
'p' => 'product/main'
);

$p['/section/'] = array(
't' => 2,
'b' => 'application',
'u' => '/(1)/',
'p' => 'product/section'
);

$p['/brand/'] = array(
't' => 2,
'b' => 'application',
'u' => '/(1)/',
'p' => 'product/section'
);

$p['/brand/section/'] = array(
't' => 2,
'b' => 'application',
'u' => '/(1)/(2)/',
'p' => 'product/section'
);

$p['/product/'] = array(
't' => 7,
'b' => 'application',
'u' => '/(1)/',
'p' => 'product/product'
);
// --------- /Товары ---------

$n = array();
$n['application']['/'] = '/index/';
$n['application']['/call-back/'] = '/call-back/';
$n['application']['/{path=news}/'] = '/news/all/';
$n['application']['/{path=news}/year/'] = '/news/year/all/';
$n['application']['/user/register/'] = '/user/register/';
$n['application']['/user/enter/'] = '/user/enter/';
$n['application']['/user/'] = '/user/';
$n['application']['/user/info/'] = '/user/info/';
$n['application']['/user/info/password/'] = '/user/info/password/';
$n['application']['/user/info/subscribe/'] = '/user/info/subscribe/';
$n['application']['/user/exit/'] = '/user/exit/';
$n['application']['/user/password/'] = '/user/password/';

$n['application']['/user/order/'] = '/user/order/';
$n['application']['/{path=search}/'] = '/search/';
$n['application']['/basket/'] = '/basket/';
$n['application']['/basket/delivery/'] = '/basket/delivery/';
$n['application']['/basket/adress/'] = '/basket/adress/';
$n['application']['/basket/subscribe/'] = '/basket/subscribe/';
$n['application']['/basket/pay/'] = '/basket/pay/';
$n['application']['/basket/confirmation/'] = '/basket/confirmation/';
$n['application']['/basket/ok/'] = '/basket/ok/';
$n['application']['/subscribe/'] = '/subscribe/';
$n['application']['/info/size/'] = '/info/size/';

$pr = array();
$pr['application']['/news/year/all/'] = array('#^/{path=news}/year/(([0-9]+)/)?(page_([0-9]+)/)?$#');
$pr['application']['/user/'] = array('#^/user/page_([0-9]+)/$#');
$pr['application']['/user/command/'] = array('#^/user/(command/.*)/$#');

$pr['application']['/user/order/'] = array('#^/user/order/page_([0-9]+)/$#');
$pr['application']['/user/order/one/'] = array('#^/user/order/([0-9]+)/$#');
$pr['application']['/user/order/pay/'] = array('#^/user/order/([0-9]+)/pay/$#');
$pr['application']['/user/order/pay/run/'] = array('#^/user/order/([0-9]+)/([^/]+)/(([^/]+)/)?$#');
$pr['application']['/user/order/result/'] = array('#^/user/order/([^/]+)/result/$#');
$pr['application']['/user/order/success/'] = array('#^/user/order/([^/]+)/success/$#');
$pr['application']['/user/order/fail/'] = array('#^/user/order/([^/]+)/fail/$#');
$pr['application']['/subscribe/command/'] = array('#^/subscribe/(command/.*)/$#');
$pr['application']['/catalog/url/'] = array('#^(/((search)/)?((.*?)/)?(name/(.*?)/)?(param/([0-9-]+)/)?((sale|new)/)?(sort/(desc|asc|new|old)/)?(page_([0-9]+)/)?(limit/([0-9]+|all)/)?)$#');
cPages::routerApplication($p, $n, $pr);
cPages::template()->set(array(
    0 => 'main.info.php',
    1 => 'main.index.php',
    2 => 'main.catalog.php',
    3 => 'basket.index.php',
    4 => 'user.index.php',
    5 => 'user.order.php',
    6 => 'print.index.php',
    7 => 'main.product.php',
));
?>