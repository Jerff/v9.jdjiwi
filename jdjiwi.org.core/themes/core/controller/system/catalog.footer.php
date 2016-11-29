<?php


$sectionId = cGlobal::get('$rootId');
$brandId = cGlobal::get('$brandId');
if($_menu = cmfCache::getParam('catalogMenu', array($sectionId, $brandId))) {
    list($_menu, $_catalog, $_brand, $isType, $baner, $banerTime) = $_menu;
} else {

    $sql = cRegister::sql();
    $where = array();
    if($sectionId) {
        $where['section'] = $sql->placeholder("SELECT id FROM ?t WHERE parent=? OR path LIKE '%[?s]%' AND isVisible='yes'", db_section, $sectionId, $sectionId)
                            ->fetchRowAll(0, 0);
        $where[] = 'AND';
        //$where['section'] = cmfGlobal::get('$sectionList');
    }
    if($brandId) {
        $where = array('brand'=>$brandId);
        //$where['brand'] = $brandId;
        $where[] = 'AND';
    }
    //pre($sectionId, $brandId, $where, cmfGlobal::get('$sectionList'));

    $_menu = cGlobal::get('$secPath');
    $sUri = cGlobal::get('$sectionUri');
    $bUri = cGlobal::get('$brandUri');
    $res = $sql->placeholder("SELECT s.id, s.parent, s.name, s.isUri, menu.isProduct FROM ?t s LEFT JOIN ?t menu ON (s.id=menu.section) WHERE ?w:menu menu.isMenu='yes' AND s.isVisible='yes' ORDER BY s.parent, s.pos", db_section, db_section_is_brand, $where)
                        ->fetchAssocAll();
    $_catalog = $_brand = array();
    foreach($res as $row) {
        $_catalog[$row['parent']][$row['id']] = array('name'=>$row['name'],
                                                      'isProduct'=>(int)$row['isProduct'],
                                                      'sel'=>isset($_menu[$row['id']]),
                                                      'url'=>$brandId ? cUrl::get('/brand/section/', $row['isUri'], $bUri) : cUrl::get('/section/', $row['isUri']));
    }

    $isType = $sql->placeholder("(SELECT 'sale', 1 FROM ?t WHERE id IN(SELECT id FROM ?t) AND ?w `type`='sale' LIMIT 0, 1)
                                  UNION
                                 (SELECT 'new', 1 FROM ?t WHERE id IN(SELECT id FROM ?t) AND ?w `created`>'". (time() - cSettings::get('catalog', 'novelty') *24*60*60) ."' LIMIT 0, 1)",
                                    db_search, db_product_id, $where,
                                    db_search, db_product_id, $where)
                        ->fetchRowAll(0, 1);


    if($sectionId) {
        $where = array('section'=>cGlobal::get('$sectionList'));
    } else {
        $where = array('section'=>0);
    }
    $res = $sql->placeholder("SELECT id, name, uri, i.isNewProduct FROM ?t b LEFT JOIN ?t i ON(b.id=i.brand) WHERE ?w:i AND b.visible='yes' AND i.isMenu='yes' ORDER BY name", db_brand, db_section_is_brand, $where)
                    ->fetchAssocAll();
    if($brandId and $sectionId) {
        $_brand[0] = array('name'=>'Все бренды',
                           'isNew'=>0,
                           'url'=>cUrl::get('/section/', $sUri));
    }
    foreach($res as $row) {
        $_brand[$row['id']] = array('name'=>$row['name'],
                                    'isNew'=>(bool)$row['isNewProduct'],
                                    'url'=>$sUri ? cUrl::get('/brand/section/', $sUri, $row['uri'])
                                                 : cUrl::get('/section/', $row['uri']));
    }
    if(isset($_brand[$brandId])) {
        $_brand[$brandId]['sel'] = true;
    }

    $where = array();
    if($sectionId) {
        $where['parent'] = cGlobal::get('$secPath');
    } else if($brandId) {
        $where['parent'] = 0;
        $where[] = 'AND';
        $where['parentBrand'] = $brandId;
    } else {
        $where['parent'] = 0;
        $where[] = 'AND';
        $where['parentBrand'] = 0;
    }
    $baner = $sql->placeholder("SELECT id, name, image, IF(`type`='edit', url, catalogUrl) AS url FROM ?t WHERE ?w AND image IS NOT NULL AND visible='yes'", db_baner, $where)
                   ->fetchAssocAll('id');
    foreach($baner as $k=>$v) {
        $baner[$k]['title'] = htmlspecialchars($v['name']);
        $baner[$k]['image'] = cBaseImgUrl . path_baner . $v['image'];
    }
    $banerTime = cSettings::get('baner', 'time');

    cmfCache::setParam('catalogMenu', array($sectionId, $brandId), array($_menu, $_catalog, $_brand, $isType, $baner, $banerTime), 'sectionList,brandList,shopList');
}

if(isset($isType['new'])) {
    $this->assing2('isNew', cGlobal::get('$isNew'));
    $this->assing2('newUrl', cUrl::get('/section/', cmfProductUrl::replace(cGlobal::get('$_itemUri'), 'param', null,
                                                                                                        'type', 'new',
                                                                                                        'page', 1)));
}
if(isset($isType['sale'])) {
    $this->assing2('isSale', cGlobal::get('$isSale'));
    $this->assing2('saleUrl', cUrl::get('/section/', cmfProductUrl::replace(cGlobal::get('$_itemUri'), 'param', null,
                                                                                                         'type', 'sale',
                                                                                                         'page', 1)));
}

$this->assing2('sectionName', cGlobal::get('$sectionName'));
$this->assing2('sectionId', (int)$sectionId);
$this->assing('_menu', $_menu);
$this->assing('_catalog', $_catalog);
$this->assing('brandId', $brandId);
$this->assing('_brand', $_brand);

if($baner) {
    shuffle($baner);
    $this->assing('baner', $baner);
    $this->assing2('banerTime', $banerTime);
}

?>