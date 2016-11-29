<?php


cLoader::library('subscribe/cSubscribeYes');
$this->assing2('subscribeYes', new cSubscribeYes());
$this->assing2('contentYes', cRegister::sql()->placeholder("SELECT content FROM ?t WHERE name='Рассылка: подписка'", db_content_static)
                            ->fetchRow(0));

cLoader::library('subscribe/cSubscribeNo');
$this->assing2('subscribeNo', new cSubscribeNo());
$this->assing2('contentNo', cRegister::sql()->placeholder("SELECT content FROM ?t WHERE name='Рассылка: отписка'", db_content_static)
                            ->fetchRow(0));
?>