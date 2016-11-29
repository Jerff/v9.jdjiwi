<?php

define('db_price_yandex', cDbPefix . 'price_yandex');

define('db_baner', cDbPefix . 'baner');
define('path_baner', cFilePath . 'baner/');

define('db_showcase', cDbPefix . 'showcase');
define('db_showcase_list', cDbPefix . 'showcase_list');
define('path_showcase', cFilePath . 'showcase/');

define('db_section', cDbPefix . 'catalog');
define('db_section_is_brand', cDbPefix . 'catalog_is_brand');
define('db_brand', cDbPefix . 'catalog_brand');
define('db_size', cDbPefix . 'catalog_size');
define('path_catalog', cFilePath . 'catalog/');


define('db_product', cDbPefix . 'product');
define('db_product_id', cDbPefix . 'product_id');
define('db_product_url', cDbPefix . 'product_url');
define('db_product_image', cDbPefix . 'product_image');
define('db_product_attach', cDbPefix . 'product_attach');
define('db_product_dump', cDbPefix . 'product_dump');
define('db_product_dump_log', cDbPefix . 'product_dump_log');
define('path_product', cFilePath . 'product/');
define('path_special', cFilePath . 'product/special/');
define('path_watermark', cFilePath . 'watermark/');


define('db_param_group', cDbPefix . 'param_group');
define('db_param_group_select', cDbPefix . 'param_group_select');
define('db_param_group_notice', cDbPefix . 'param_group_notice');
define('db_color', cDbPefix . 'param_group_color');

define('db_param_discount', cDbPefix . 'param_group_discount');
define('path_discount', cFilePath . 'discount/');

define('db_discount', cDbPefix . 'basket_discount');

define('db_param', cDbPefix . 'param');
define('db_param_select', cDbPefix . 'param_select');
define('db_param_checkbox', cDbPefix . 'param_checkbox');


define('db_main', cDbPefix . 'main');
define('db_main_image', cDbPefix . 'main_image');
define('path_image', cFilePath . 'image/');

define('db_menu', cDbPefix . 'menu');

define('db_content', cDbPefix . 'content');
define('db_content_pages', cDbPefix . 'content_pages');
define('db_content_info', cDbPefix . 'content_info');
define('db_content_static', cDbPefix . 'content_static');
define('db_content_url', cDbPefix . 'content_url');


define('db_news', cDbPefix . 'news');
define('path_news', cFilePath . 'news/');

define('db_article', cDbPefix . 'article');
define('db_article_attach', cDbPefix . 'article_attach');
define('path_article', cFilePath . 'article/');

define('db_photo', cDbPefix . 'photo');
define('db_photo_image', cDbPefix . 'photo_image');
define('path_photo', cFilePath . 'photo/');

define('db_search', cDbPefix . 'search');


// кoрзина
define('db_basket', cDbPefix . 'basket');
define('db_basket_order', cDbPefix . 'basket_order');
define('db_basket_status', cDbPefix . 'basket_status');

define('db_payment', cDbPefix . 'payment');
define('path_Payment', cFilePath . 'pay/');
define('db_payment_log', cDbPefix . 'payment_log');
define('db_payment_transactions', cDbPefix . 'payment_transactions');

define('db_delivery', cDbPefix . 'delivery');

define('db_sms_inform', cDbPefix . 'sms_inform');
define('db_delivery_region', cDbPefix . 'delivery_region');


// Рассылка
define('db_subscribe', cDbPefix . 'subscribe');
define('db_subscribe_history', cDbPefix . 'subscribe_history');
define('db_subscribe_mail', cDbPefix . 'subscribe_mail');
define('db_subscribe_status', cDbPefix . 'subscribe_status');

define('db_subscribe_user', cDbPefix . 'subscribe_user');
define('db_subscribe_user_status', cDbPefix . 'subscribe_user_status');


// Визауальный редактор
define('cWysiwyngPath', cWWWPath . 'wysiwyng/');
define('path_wysiwyng', cFilePath . 'wysiwyng/');


// Почта
define('db_mail_templates', cDbPefix . 'mail_templates');
define('db_mail_var', cDbPefix . 'mail_var');
define('db_mail_list', cDbPefix . 'mail_list');
define('db_mail_config', cDbPefix . 'mail_config');



//seo
define('db_seo_title', cDbPefix . 'seo_title');
define('db_seo_counters', cDbPefix . 'seo_counters');
define('db_seo_sitemap', cDbPefix . 'seo_sitemap');


//user
define('db_user', cDbPefix . 'user');
define('db_user_ses', cDbPefix . 'user_ses');
define('db_user_data', cDbPefix . 'user_data');
define('db_user_group_admin', cDbPefix . 'user_group_admin');


// система
define('db_sys_limit', cDbPefix . 'sys_limit');
define('db_backup_site', cDbPefix . 'sys_backup');
define('db_sys_settings', cDbPefix . 'sys_settings');
define('path_config', cFilePath . 'config/');
define('db_sys_cron', cDbPefix . 'sys_cron');

// страницы
define('db_pages_admin', cDbPefix . 'sys_pages_admin');
define('db_pages_main', cDbPefix . 'sys_pages_main');
define('db_access_read', cDbPefix . 'sys_access_read');
define('db_access_write', cDbPefix . 'sys_access_write');
define('db_access_delete', cDbPefix . 'sys_access_delete');


// кеш меню админки
define('db_admin_cache', cDbPefix . 'sys_cache_admin');
// кеш данных
define('db_cache_data', cDbPefix . 'sys_cache_data');
// обновление кеша
define('db_cache_update', cDbPefix . 'sys_cache_update');
?>