<?php


class cmfCronConfig {

    static public function menu() {
        $menu = array();
        $menu['product.dump']  = 'Синхранихация размеров';
        $menu['updateCache']   = 'Обновление кеша';
        $menu['siteMap']       = 'Генерация siteMap';
        $menu['updateSearch']  = 'Обновление поискового индекса';
        $menu['user.activate'] = 'Удаление неактивированных аккурантов пользователей';
        $menu['Yandex.Market'] = 'Генерация Yandex.Market';
        $menu['subscribe']     = 'Рассылка';
        $menu['backup']        = 'Резервное копирование';
        $menu['basket.drlivery.status'] = 'Отслеживание посылок';
		return $menu;
    }


    static public function runModul($name) {
        switch($name) {
            case 'updateCache':
                cmfCronCacheUpdate::start();
                break;

            case 'siteMap':
                cmfCronSiteMap::run();
                break;

            case 'updateSearch':
				cmfSearchData::update();
				break;

			case 'subscribe':
				cmfCronSubscribe::run();
				break;

			case 'backup':
				cmfCronBackup::run();
				break;

			case 'Yandex.Market':
				cmfCronYandexMarket::run();
				break;

			case 'user.activate':
				cmfCronUserActivateClear::run();
				break;

			case 'product.dump':
				cmfCronProductDump::update();
				break;

			case 'basket.drlivery.status':
				cmfCronBasketDeliveryStatus::update();
				break;
		}
	}

}

?>
