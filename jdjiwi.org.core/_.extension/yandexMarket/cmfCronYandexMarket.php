<?php

cLoader::library('catalog/function');
//cmfLoad('product/param');
cLoader::library('cron/cmfCronUpdateDriver');
class cmfCronYandexMarket extends cmfCronUpdateDriver {

    static protected function file() {
        return 'price/yandex/price.xml';
    }

    static public function getFile() {
        return cWWWPath . self::file();
    }

    static public function getUrlFile() {
        return cBaseAppUrl . self::file();
    }

    static public function run() {
        $conf = cSettings::read('yandex.market');
        $date = date('Y-m-d H:i');
        $index = cBaseAppUrl;
        $file = fopen(self::getFile(), 'w');


        $content =<<<HTML
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="{$date}">
<shop>
    <name>{$conf['name']}</name>
    <company>{$conf['company']}</company>
    <url>{$index}</url>

    <currencies>
        <currency id="RUR" rate="1"/>
    </currencies>

    <categories>
HTML;
        fwrite($file, $content);


        list($_section, $_sectionPath) = self::getSection();
        cmfCronRun::run();
        foreach($_section as $id=>$row) {
            $html = '
        <category id="'. $id .'"'. ($row['parent'] ? ' parentId="'. $row['parent'] .'"' : '') .'>'. $row['name'] .'</category>';
            fwrite($file, $html);
        }

        $content =<<<HTML

    </categories>

    <offers>
HTML;
        fwrite($file, $content);


        self::getProduct($file, $conf, $_section, $_sectionPath);


        $content =<<<HTML

    </offers>
</shop>
</yml_catalog>
HTML;
        fwrite($file, $content);
        fclose($file);
        cmfCronRun::free();
    }

    static public function getSection() {
        $res = cRegister::sql()->placeholder("SELECT id, parent, path, basket, name, stop FROM ?t WHERE id IN(SELECT section FROM ?t WHERE brand='0' AND isMenu='yes') AND isVisible='yes' ORDER BY `path`, pos", db_section, db_section_is_brand)
                                    ->fetchAssocAll();
        $_section = $_sectionPath = $_path = $_pathId = array();
        foreach($res as $row) {
            $tmp = cConvert::pathToArray($row['path']);
            $tmp[] = $row['id'];
            $_pathId = array_merge($_pathId, $tmp);
            $_path[$row['id']] = $tmp;
            $_section[$row['id']] = array(  'parent'=>$row['parent'],
                                            'path'=>$tmp,
                                            'basket'=>$row['basket'],
                                            'stop'=>array_map('trim', explode(',', $row['stop'])),
                                            'name'=>cString::specialchars($row['name']));
        }

        $name = cRegister::sql()->placeholder("SELECT id, name FROM ?t WHERE id ?@", db_section, $_pathId)
                                    ->fetchAssocAll('id');
        foreach($_path as $id=>$row) {
            $str = $sep = '';
            foreach($row as $k) {
                $str .= $sep . $name[$k];
                $sep = ' / ';
            }
            $_sectionPath[$id] = $str;
        }
        return array($_section, $_sectionPath);
    }


    static public function getProduct($file, $conf, $_section, $_sectionPath) {
        $sql = cRegister::sql();

        $isNotice = $conf['notice'];
        $isName = $conf['product'];
        if($conf['sales_notes']) {
            $salesNotes = "
                <sales_notes>". htmlspecialchars($conf['sales_notes']) ."</sales_notes>";
        } else {
            $salesNotes = '';
        }

        $_param = array();
        $_color = $sql->placeholder("SELECT id, name FROM ?t WHERE visible='yes' ORDER BY name", db_color)
                    ->fetchRowAll(0, 1);
        $_brand = $sql->placeholder("SELECT id, name FROM ?t WHERE visible='yes'", db_brand)
                    ->fetchRowAll(0, 1);
        $res = $sql->placeholder("SELECT p.id, p.section, p.brand, p.name, p.param, p.notice, p.colorAll AS color, p.notice, p.salesNotes, u.url, p.price, IF(p.type!='sale' AND p.discount='1', '1', '0') AS isDiscount, p.image_section AS image FROM ?t p LEFT JOIN ?t u ON(u.product=p.id AND u.brand='0') WHERE p.section ?@ AND p.brand ?@ AND p.price>=? ORDER BY name", db_product, db_product_url, array_keys($_section), array_keys($_brand), $conf['price'])
                                    ->fetchAssocAll();
        foreach($res as $k=>$row) {
            if(empty($row['param'])) continue;
            cmfCronRun::run();
            $section = $row['section'];

            switch($isNotice) {
                case 'param':
                case 'param+content':
                    if(!isset($_param[$section])) {
                        $_param[$section] = cmfParam::getNotice($_section[$section]['path'], $_section[$section]['basket']);
                    }
                    list(, $paramList) = $_param[$section];
                    $param = cConvert::unserialize($row['param']);
                    $param = cmfParam::generateNotice($param, $paramList);

                    $color = cConvert::pathToArray($row['color']);
                    $item = $_color;
                    foreach($item as $k=>$v) {
                        if(!isset($color[$k])) {
                            unset($item[$k]);
                        }
                    }
                    if($item) {
                        $param['Цвет'] = implode(', ', $item);
                    }
                    break;

                default:
                    $param = array();
                    break;
            }
            switch($isNotice) {
                case 'param':
                case 'none':
                    $description = '';
                    break;

                case 'param+content':
                case 'content':
                    $description = cString::specialchars(strip_tags($row['notice']));
                    break;
            }

            $row['name'] = trim(str_replace($_section[$section]['stop'], '', $row['name']));
            switch($isName) {
                case 'name':
                    $name = $row['name'];
                    break;

                case 'section':
                    $name = $_section[$section]['name'] .': '. $row['name'];
                    break;

                case 'path':
                    $name = $_sectionPath[$section] .': '. $row['name'];
                    break;
            }
            $name = cString::specialchars($name);
            $brand = cString::specialchars($_brand[$row['brand']]);
            $url = cString::specialchars(cUrl::get('/product/', $row['url']));
            $description = cString::specialchars($description);
            $salesNotes = cString::specialchars($row['salesNotes']);
            $dump = $row['url']==='yes' ? 'true' : 'false';
            $image = $row['image'] ? cString::specialchars(cBaseImgUrl . path_product . $row['image']) : null;

            $html =<<<HTML

        <offer id="{$row['id']}" type="vendor.model" available="{$dump}">
            <url>{$url}</url>
            <price>{$row['price']}</price>
            <currencyId>RUR</currencyId>
            <categoryId>{$section}</categoryId>
HTML;

            if($image) {
                $html .="
            <picture>{$image}</picture>";
            }

            $html .=<<<HTML

            <delivery>true</delivery>
            <vendor>{$brand}</vendor>
            <model>{$name}</model>
            <description>{$description}</description>
HTML;

            if($salesNotes) {
                $html .="
            <sales_notes>{$salesNotes}</sales_notes>";
            }

            if($param) {
                foreach($param as $k=>$v) {
                    $html .="
            <param name=\"". cString::specialchars($k) ."\">". cString::specialchars($v) ."</param>";
                }
            }

            $html .=<<<HTML

        </offer>
HTML;
            fwrite($file, $html);
        }
    }

}

?>