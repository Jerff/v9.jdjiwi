<?php

$content = cRegister::sql()->placeholder("SELECT content FROM ?t WHERE name='Корзина заказа: заказ принят'", db_content_static)
							->fetchRow(0);
$this->assing('content', $content);

$menu = array();
$menu[] = array('name'=>'Корзина');
cGlobal::set('$headerMenu', $menu);

?>