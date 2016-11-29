<?php

class model_product extends cmfDriverModel {

    const name = '/product/';

    static public function update($id = null, $send = null) {
        if (self::isUpdate('section|brand|uri|visible', $send)) {
            self::isProduct();
        }
        if (self::isUpdate('section|brand|uri', $send)) {
            if (self::isUpdate('section', $send)) {
                model_catalog_section::updateUri($send['section']);
            } elseif (self::isUpdate('brand', $send)) {
                model_catalog_brand::updateUri(get($send, 'section'), $send['brand']);
            } else {
                self::updateUri(null, null, $id);
            }
            self::updateSearchId();
            cmfContentUrl::updateShowcase();
        }
        if (self::isUpdate('price1|price2|discount|type', $send)) {
            self::updatePrice($id);
        }
        if (self::isUpdate('color', $send)) {
            self::updateColor($id);
        }

        self::updateSearch(null, null, $id);
    }

    static protected function isVisible() {
        $is = array();
        $is['visible'] = 'yes';
        return $is;
    }

    static protected function isVisibleSearch() {
        $is = array();
        $is['visible'] = 'yes';
        $is[] = 'AND';
        $is[] = "(`count`>'0' OR (`count`='0' AND `isOrder`='yes'))";
        return $is;
    }

    static public function updatePrice($id) {
        self::updateWhere($id);
        cRegister::sql()->placeholder("UPDATE ?t p SET p.isUpdate='yes', p.price=ROUND(IF(p.price1, p.price1, p.price2) * IF(p.type='sale', 1, p.discount)) WHERE ?w", db_product, $id);
    }

    static public function updateColor($list) {
        $sql = cRegister::sql();
        foreach ((array) $list as $id) {
            $res = $sql->placeholder("(SELECT color FROM ?t WHERE id=?)
                                      UNION
                                      (SELECT color FROM ?t WHERE product=?)", db_product, $id, db_product_image, $id)
                    ->fetchAssocAll();
            $color = array();
            foreach ($res as $row) {
                $color = array_merge($color, cConvert::pathToArray($row['color']));
            }
            $sql->placeholder("UPDATE ?t SET colorAll=? WHERE id=?", db_product, сSConvert::arrayToPath(array_unique($color)), $id);
        }
    }

    static public function updateCache($id) {
        cmfUpdateCache::update('product', $id);
    }

    static public function delete($id) {
        cmfContentUrl::deleteWhere('/product/', array('product' => $id));
        cmfSearchData::delete(self::name, array('id' => $id));
        self::isProduct();
        self::updateSearchId();
    }

    static private function startDbProductDd() {
        cRegister::sql()->placeholder(" CREATE TEMPORARY TABLE IF NOT EXISTS `db_product_id` (
                                              `id` mediumint(8) unsigned NOT NULL,
                                              PRIMARY KEY  (`id`))");
        cRegister::sql()->placeholder("REPLACE INTO `db_product_id` SELECT id FROM ?t WHERE section IN(SELECT id FROM ?t WHERE isVisible='yes') AND brand IN(SELECT id FROM ?t WHERE visible='yes') AND ?w", db_product, db_section, db_brand, self::isVisible());
    }

    static public function updateUri($sec = null, $brand = null, $product = null) {
        self::updateWhere($sec);
        self::updateWhere($brand);
        self::updateWhere($product);

        /* товары */
        cRegister::sql()->placeholder("
                REPLACE ?t SELECT '/product/', sec.id, 0, p.id, CONCAT_WS('/', sec.isUri, p.uri) FROM ?t p LEFT JOIN ?t sec ON(sec.id=p.section) WHERE sec.id=p.section AND ?w:sec AND ?w:p", db_content_url, db_product, db_section, $sec, $product);
        /* brand */
        /* cRegister::getSql()->placeholder("
          REPLACE ?t SELECT '/product/', sec.id, b.id, p.id, CONCAT_WS('/', sec.isUri, b.uri, p.uri) FROM ?t p LEFT JOIN ?t b ON(b.id=p.brand) LEFT JOIN ?t sec ON(sec.id=p.section) WHERE sec.id=p.section AND b.id=p.brand AND ?w:sec AND ?w:b AND ?w:p",
          db_content_url, db_product, db_brand, db_section, $sec, $brand, $product); */
        self::startDbProductDd();
        cRegister::sql()->placeholder("
                DELETE FROM ?t WHERE page='/product/' AND product NOT IN(SELECT id FROM `db_product_id`)", db_content_url);
    }

    static public function updateSearchId() {
        $sql = cRegister::sql();
        $sql->placeholder("UPDATE ?t SET isNew='no'", db_search);
        $sql->placeholder("UPDATE ?t SET visible='yes', isNew='yes' WHERE id IN(SELECT id FROM ?t WHERE section IN(SELECT id FROM ?t WHERE visible='yes') AND brand IN(SELECT id FROM ?t WHERE visible='yes') AND ?w)", db_search, db_product, db_section, db_brand, self::isVisibleSearch());
        $sql->placeholder("UPDATE ?t SET visible='no' WHERE isNew='no'", db_search);

        self::startDbProductDd();
        $sql->placeholder("REPLACE INTO ?t SELECT product, brand, url FROM ?t WHERE `page`='/product/' AND product IN(SELECT id FROM `db_product_id`)", db_product_url, db_content_url);
        $sql->placeholder("DELETE FROM ?t WHERE product NOT IN(SELECT id FROM `db_product_id`)", db_product_url, db_product_id);
        $sql->placeholder("DELETE FROM ?t WHERE id NOT IN(SELECT id FROM ?t)", db_search, db_product_id);

        $sql->placeholder("UPDATE ?t SET visible='yes' WHERE id IN(SELECT id FROM ?t)", db_search, db_product_id);
        $sql->placeholder("UPDATE ?t SET visible='no' WHERE id NOT IN(SELECT id FROM ?t)", db_search, db_product_id);
    }

    static public function updateSearch($sec = null, $brand = null, $product = null) {
        self::updateWhere($sec);
        self::updateWhere($brand);
        self::updateWhere($product);

        $sql = cRegister::sql();
        $section = $sql->placeholder("SELECT id, path FROM ?t", db_section)
                ->fetchRowAll(0, 1);
        foreach ($section as $id => $path) {
            $parent = array();
            foreach (cConvert::pathToArray($path) as $v) {
                $parent[$v] = $v;
            }
            $parent[$id] = $id;
            $section[$id] = $parent;
        }

        $filterAll = $sql->placeholder("SELECT `group`, param FROM ?t WHERE ((CEIL(param)>0 AND param IN(SELECT id FROM ?t WHERE visible='yes')) OR (CEIL(param)=0)) AND visible='yes' ORDER BY pos", db_param_group_select, db_param)
                ->fetchRowAll(0, 1, 1);
        foreach ($filterAll as $p => $list) {
            $list = array_splice($list, 0, 8);
            $filterAll[$p] = array_combine($list, $list);
        }
        $param = $sql->placeholder("SELECT id, type FROM ?t WHERE id IN(SELECT param FROM ?t WHERE visible='yes')", db_param, db_param_group_select)
                ->fetchRowAll(0, 1);
        $sql->placeholder("UPDATE ?t SET isNew='no'", db_search);

        $_basketId = array();
        $res = $sql->placeholder("SELECT section, brand, id, name, articul, discount, `type`, colorAll as color, paramDump, isOrder, created, price, CONCAT(name, ' ', notice) AS content FROM ?t WHERE section IN(SELECT id FROM ?t WHERE ?w AND visible='yes') AND brand IN(SELECT id FROM ?t WHERE ?w AND visible='yes') AND visible='yes' AND ?w AND ?w", db_product, db_section, $sec, db_brand, $brand, self::isVisibleSearch(), $product)
                ->fetchAssocAll();
        $list = array();
        foreach ($res as $row) {
            $row['content'] = cmfSearchData::reformStr($row['articul'] . ' ' . $row['name'] . ' ' . $row['content']);
            unset($row['name']);

            $id = $row['id'];
            $list[] = $id;

            $filter = get($filterAll, 0, array());
            foreach ($section[$row['section']] as $group) {
                if (isset($filterAll[$group])) {
                    foreach ($filterAll[$group] as $f) {
                        $filter[$f] = $f;
                    }
                }
            }

            $isNotOrder = $row['isOrder'] === 'no';
            $productDump = cConvert::unserialize($row['paramDump']);
            unset($row['isOrder'], $row['paramDump']);
            if ($filter) {
                if (isset($_basketId[$sectionId = $row['section']])) {
                    $basketId = $_basketId[$sectionId];
                } else {
                    $product = $sql->placeholder("SELECT s.basket AS secBasket, s.path AS secPath FROM ?t s WHERE s.id=?", db_section, $sectionId)
                            ->fetchAssoc();
                    $path = cConvert::pathToArray($product['secPath']);
                    $path[$sectionId] = $sectionId;
                    list(,, $basketId) = cmfParam::getNotice($path, $product['secBasket']);
                    $_basketId[$sectionId] = $basketId;
                }

                $data = $sql->placeholder("(SELECT param, value FROM ?t WHERE id=? AND param IN(SELECT id FROM ?t WHERE visible='yes') AND param ?@) UNION (SELECT param, value FROM ?t WHERE id=? AND param IN(SELECT id FROM ?t WHERE visible='yes') AND  param ?@)", db_param_select, $id, db_param, $filter, db_param_checkbox, $id, db_param, $filter)
                        ->fetchRowAll(0, 1, 1);
                foreach ($filter as $k => $v) {
                    if (isset($data[$v])) {
                        $filter[$k] = $data[$v];
                        if ($k == $basketId) {
                            foreach ($filter[$k] as $k2 => $v2) {
                                if ($isNotOrder and empty($productDump[$k2])) {
                                    unset($filter[$k][$k2]);
                                }
                            }
                        }
                    } else {
                        $filter[$k] = array();
                    }
                }

                if (isset($filter['color'])) {
                    $filter['color'] = cConvert::pathToArray($row['color']);
                } else {
                    $filter['color'] = array(0);
                }
                unset($row['color'], $filter['discount'], $filter['price']);
                $row['isNew'] = 'yes';
                self::saveProduct($filter, $row);
            } else {
                $filter['color'] = array(0);
                $row['isNew'] = 'yes';
                self::saveProduct($filter, $row);
            }
            cmfCronRun::run();
        }
        $sql->del(db_search, array('id' => $list, 'AND', 'isNew' => 'no'));
        $sql->placeholder("DELETE FROM ?t WHERE id NOT IN(SELECT id FROM ?t)", db_search, db_product_id);
    }

    static private function saveProduct($filter, $row, $i = 1) {
        if (!$filter) {
            cRegister::sql()->replace(db_search, $row);
            return;
        }
        $value = $filter[$key = key($filter)];
        unset($filter[$key]);
        if ($key === 'color') {

        } else {
            $key = 'param' . ($i++);
        }
        if ($value) {
            foreach ((array) $value as $v) {
                $row[$key] = $v;
                self::saveProduct($filter, $row, $i);
            }
        } else {
            self::saveProduct($filter, $row, $i);
        }
    }

    static public function isProduct() {
        $sql = cRegister::sql();

        $sql->placeholder("REPLACE INTO ?t SELECT id, section, brand FROM ?t WHERE section IN(SELECT id FROM ?t WHERE isVisible='yes') AND brand IN(SELECT id FROM ?t WHERE visible='yes') AND ?w", db_product_id, db_product, db_section, db_brand, self::isVisibleSearch());
        $sql->placeholder("DELETE FROM ?t WHERE id NOT IN(SELECT id FROM ?t WHERE section IN(SELECT id FROM ?t WHERE isVisible='yes') AND brand IN(SELECT id FROM ?t WHERE visible='yes') AND ?w)", db_product_id, db_product, db_section, db_brand, self::isVisibleSearch());

        $sql->placeholder("REPLACE INTO ?t SELECT sec.id , 0,    sec.path, 'no', 0, 0 FROM ?t sec WHERE sec.isVisible='yes'", db_section_is_brand, db_section);
        $sql->placeholder("REPLACE INTO ?t SELECT sec.id , b.id, sec.path, 'no', 0, 0 FROM ?t list LEFT JOIN ?t sec ON(sec.id=list.section) LEFT JOIN ?t sec2 ON(sec.id=sec2.id OR sec.path LIKE CONCAT('%[', sec2.id, ']%')) LEFT JOIN ?t b ON(list.brand=b.id) WHERE sec.isVisible='yes' AND b.visible='yes' GROUP BY sec2.id, b.id", db_section_is_brand, db_product_id, db_section, db_section, db_brand);
        $sql->placeholder("DELETE FROM ?t WHERE section NOT IN(SELECT id FROM ?t WHERE isVisible='yes') OR (brand!=0 AND brand NOT IN(SELECT id FROM ?t WHERE visible='yes'))", db_section_is_brand, db_section, db_brand);
        $sql->placeholder("REPLACE INTO ?t SELECT 0 , b.id, '', 'no', 0, 0 FROM ?t b WHERE b.visible='yes'", db_section_is_brand, db_brand);

        $sql->placeholder("UPDATE ?t s SET s.isProduct=(SELECT count(*) FROM ?t p WHERE p.section=s.section)                     WHERE s.brand='0'", db_section_is_brand, db_product_id);
        $sql->placeholder("UPDATE ?t s SET s.isProduct=(SELECT count(*) FROM ?t p WHERE p.section=s.section AND p.brand=s.brand) WHERE s.brand!='0'", db_section_is_brand, db_product_id, db_product_id, db_product);
        $sql->placeholder("UPDATE ?t s SET s.isProduct=(SELECT count(*) FROM ?t p WHERE p.brand=s.brand) WHERE s.section='0'", db_section_is_brand, db_product_id);
        $sql->placeholder("UPDATE ?t s SET s.isNewProduct=(SELECT count(*) FROM ?t p WHERE p.brand=s.brand AND p.id IN(SELECT t.id FROM ?t t WHERE t.created>" . (time() - cSettings::read('catalog', 'novelty') * 24 * 60 * 60) . "))
		                                                                                                                         WHERE s.brand!='0' AND s.section='0'", db_section_is_brand, db_product_id, db_product);
        $sql->placeholder(" CREATE TEMPORARY TABLE IF NOT EXISTS `db_section` (
                              `section` smallint(5) unsigned NOT NULL,
                              `brand` smallint(5) unsigned NOT NULL default '0',
                              `path` tinytext character set utf8,
                              `isMenu` enum('yes','no') character set utf8 NOT NULL default 'no',
                              `isProduct` smallint(5) unsigned NOT NULL default '0',
                              PRIMARY KEY  (`section`,`brand`))");
        $sql->placeholder("REPLACE INTO `db_section` SELECT section, brand, path, isMenu, isProduct FROM ?t", db_section_is_brand);
        $sql->placeholder("UPDATE ?t s SET s.isMenu=IF(s.isProduct>0,
                                        'yes',
                                        IF((SELECT 1 FROM `db_section` t WHERE t.path LIKE CONCAT('%[', s.section ,']%') AND t.isProduct>0 AND t.brand=s.brand LIMIT 0, 1), 'yes', 'no')
                                        ) WHERE s.brand!='0';
                            ", db_section_is_brand);
        $sql->placeholder("UPDATE ?t s SET s.isMenu=IF(s.isProduct>0,
                                        'yes',
                                        IF((SELECT 1 FROM `db_section` t WHERE t.path LIKE CONCAT('%[', s.section ,']%') AND t.isProduct>0 LIMIT 0, 1), 'yes', 'no')
                                        ) WHERE s.brand='0'", db_section_is_brand);

        $sql->optimize(db_section_is_brand, db_product_url, db_product_id);
    }

}

?>