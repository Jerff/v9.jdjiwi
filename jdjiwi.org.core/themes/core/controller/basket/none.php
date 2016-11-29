<?php

$content = cRegister::sql()->placeholder("SELECT content FROM ?t WHERE name='Корзина заказа: Корзина пуста'", db_content_static)
                            ->fetchRow(0);
$this->assing('content', $content);

?>