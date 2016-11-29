<?php

cLoader::library('catalog/function');
class cmfCronProductDump extends cmfCronUpdateDriver {

    static public function update() {
        cmfCronRun::run();
        $sql = cRegister::sql();
        $res = $sql->placeholder("SELECT name, param, value FROM ?t", db_product_dump)
                   ->fetchAssocAll('name');
        $_parser = $_param = array();
        foreach($res as $k=>$v) {
            $_parser[cString::strtolower($k)] = $v;
            $_param[$v['param']] = $v['param'];
        }

        $res = $sql->placeholder("SELECT id, value FROM ?t WHERE id ?@", db_param, $_param)
                   ->fetchAssocAll();
        foreach($res as $row) {
            $_param[$row['id']] = cConvert::unserialize($row['value']);
        }


        $isUpdate = false;
        $res = $sql->placeholder("SELECT id, name, dumpUrl, param FROM ?t WHERE isOrder='yes' AND LENGTH(dumpUrl)>'0'", db_product)
                   ->fetchAssocAll();
        foreach($res as $row) {
            cmfCronRun::run();

            $url = cUrl::admin()->get('/admin/product/edit/') .'id='. $row['id'];
            $url = "<a href='{$url}' target='_blank'>{$row['name']}</a>";
            $param = cConvert::unserialize($row['param']);
            $content = file_get_contents($row['dumpUrl']);
            if(empty($content)) {
                self::messageImportant("недоступна страница товара {$url}");
                continue;
            }

            $content = strip_tags($content, '<select><option>');
            $start = preg_quote('<select class="input" id="var_value" name="var_value_');
            $end = preg_quote('</select>');
            preg_match("~($start.*$end)~msU", $content, $tmp);
            if(empty($tmp[1])) {
                self::messageImportant("обновите парсер {$url}");
                continue;
            }
            $start = preg_quote('<option');
            $end = preg_quote('</option>');
            preg_match_all("~($start.*$end)~msU", $tmp[1], $tmp2);
            if(empty($tmp[1])) {
                self::messageImportant("обновите парсер {$url}");
                continue;
            }

            $new = $message = array();
            foreach($tmp2[1] as $value) {
                $value = cString::strtolower(trim(strip_tags($value)));
                if(!isset($_parser[$value])) continue;
                $pId = $_parser[$value]['param'];
                $vId = $_parser[$value]['value'];
                $new[$pId][$vId] = $vId;
                $message[] = $_param[$pId][$vId];
            }
            foreach($new as $k=>$v) {
                $param[$k] = $v;
            }
            $isUpdate = true;
            $sql->add(db_product, array('param'=>cConvert::serialize($param)), $row['id']);
            self::message("обновлены размеры у товара {". implode(', ', $message) ."} {$url}");
        }
        if($isUpdate) {
            cmfCronUpdateSearch::init();
            $sql->placeholder("DELETE FROM ?t WHERE date<?", db_product_dump_log, date('Y-m-d', time()-15*24*60*60));
        }
        cmfCronRun::free();
    }

    static private function messageImportant($message) {
        self::message($message, true);
    }

    static private function message($message, $important=false) {
        cRegister::sql()->add(db_product_dump_log, array('date'=>date('Y-m-d H:i:s'),
                                                          'content'=>$message,
                                                          'important'=>$important? 'yes' : 'no'));
    }

}

?>