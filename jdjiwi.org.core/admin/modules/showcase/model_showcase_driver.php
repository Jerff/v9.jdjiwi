<?php


class model_showcase_driver {


    static public function updateUri($db) {
        cRegister::sql()->placeholder("
                UPDATE ?t shop SET shop.catalogUrl=CONCAT('/', (SELECT sec.isUri FROM ?t sec WHERE sec.id=shop.section), '/')
                               WHERE shop.section IN(SELECT id FROM ?t) AND shop.brand='0' AND shop.product='0'",
                $db, db_section, db_section);
        self::updateBrandUri($db);
    }

    static public function updateBrandUri($db) {
        cRegister::sql()->placeholder("
                UPDATE ?t shop SET shop.catalogUrl=CONCAT('/', (SELECT sec.isUri FROM ?t sec WHERE sec.id=shop.section), '/',
                                                               (SELECT brand.uri FROM ?t brand WHERE brand.id=shop.brand), '/')
                               WHERE shop.section IN(SELECT id FROM ?t) AND shop.brand IN(SELECT id FROM ?t) AND shop.product='0'",
                $db, db_section, db_brand, db_section, db_brand);
        self::updateProductUri($db);
    }

    static public function updateProductUri($db) {
        cRegister::sql()->placeholder("
                UPDATE ?t shop SET shop.catalogUrl=CONCAT('/', (SELECT sec.isUri FROM ?t sec WHERE sec.id=shop.section), '/',
                                                               (SELECT product.uri FROM ?t product WHERE product.id=shop.product), '/')
                               WHERE shop.section IN(SELECT id FROM ?t) AND shop.brand IN(SELECT id FROM ?t) AND shop.product IN(SELECT id FROM ?t)",
                $db, db_section, db_brand, db_product, db_section, db_brand, db_product);
        cRegister::sql()->placeholder("
                UPDATE ?t shop SET shop.catalogUrl=CONCAT('/', (SELECT sec.isUri FROM ?t sec WHERE sec.id=shop.section), '/',
                                                               (SELECT product.uri FROM ?t product WHERE product.id=shop.product), '/')
                               WHERE shop.section IN(SELECT id FROM ?t) AND shop.brand='0' AND shop.product IN(SELECT id FROM ?t)",
                $db, db_section, db_product, db_section, db_product);
	}

}

?>