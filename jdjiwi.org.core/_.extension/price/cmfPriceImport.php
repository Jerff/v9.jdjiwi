<?php


class cmfPriceImport {

    protected $sheet = null;
	protected $xml = null;

    protected $dump = array();
    protected $basket = array();
    protected function push($parent, $basket) {
        $this->dump[] = $parent;
        $this->basket[] = $basket ? $basket : end($this->basket);
    }
    protected function pop() {
        array_pop($this->dump);
        array_pop($this->basket);
        return (int)end($this->dump);
    }
    protected function get() {
        return (int)end($this->basket);
    }


    protected $_log = array();
    protected function addLog( $message) {
        $this->_log[] = $message;
    }
    protected function saveLog($shop) {
        if($this->_log)
        cRegister::sql()->add(db_shop_import, array('shop'=>$shop,
                                                         'date'=>date('Y-m-d H:i:s'),
                                                         'text'=>implode('<br />', $this->_log)));
    }

    public function __construct($shop, $file) {
        $sql = cRegister::sql();

        cLoader::library('excel_reader2/excel_reader2');
        $data = new Spreadsheet_Excel_Reader($file);
        $data = $data->sheets[0]['cells'];
        $count = count($data);
        $section = 0;

        $_brand = $sql->placeholder("SELECT id, name FROM ?t", db_brand)
                            ->fetchRowAll(1, 0);
        $_color = $sql->placeholder("SELECT id, name FROM ?t", db_color)
                            ->fetchRowAll(1, 0);

        $_param = $sql->placeholder("SELECT id, name, value, type FROM ?t WHERE value!='' AND value IS NOT NULL", db_param)
                            ->fetchAssocAll('name');
        foreach($_param as $k=>$v) {
            $_param[$k]['value'] = cConvert::unserialize($v['value']);
        }

        $_basket = $sql->placeholder("SELECT id, value FROM ?t WHERE value!='' AND value IS NOT NULL", db_param)
                            ->fetchRowAll(0, 1);
        foreach($_basket as $k=>$v) {
            $_basket[$k] = cConvert::unserialize($v);
        }

        $list = array();
        for($i=2; ; $i++) {
            if(!isset($data[$i]))  break;
            $row = $data[$i];
            if(count($row)>1) {
                if(!$section) {
                    continue;
                }
                array_map('trim', $row);
                $articul = get($row, 1);
                $bName = get($row, 2);
                $name = get($row, 3);
                $color = get($row, 4);
                $price1 = (int)get($row, 5);
                $discount = (int)get($row, 6);
                $paramPrice = get($row, 8);
                $param = get($row, 9);
                $dump = get($row, 10)==='да' ? 'yes' : 'no';
                $visible = get($row, 11)==='да' ? 'yes' : 'no';

                $id = $sql->placeholder("SELECT id FROM ?t WHERE shop=? AND articul=?", db_product, $shop, $articul)
                            ->fetchRow(0);
                if(!$id) {
                    $this->addLog("ненайден артикул: $articul");
                    continue;
                }
                $list[] = $id;

                if(!isset($_brand[$bName])) {
                    $this->addLog("ненайден производитель к артикулу: $articul");
                    continue;
                }

                $send = array();
                $send['dump'] = $dump;
                $send['visible'] = $visible;
                $send['name'] = $name;
                $send['brand'] = $_brand[$bName];
                $send['section'] = $section;
                $send['discount'] = 1-$discount/100;
                $send['price1'] = $price1;

                $new = array();
                foreach(array_map('trim', explode(';', $color)) as $c) {
                    if(isset($_color[$c])) {
                        $new[] = $_color[$c];
                    } else {
                        $this->addLog("отсутсвует цвет в базе: $c");
                    }
                }
                $send['color'] = сSConvert::arrayToPath($new);

                $new = array();
                foreach(array_map('trim', explode(";", $param)) as $v) {
                    $v = array_map('trim', explode(":", $v));
                    if(isset($v[1])) {
                        list($n, $d) = $v;
                        if(isset($_param[$n])) {
                            $p = $_param[$n];
                            if($p['type']==='checkbox' or $p['type']==='basket') {
                                $new2 = array();
                                foreach(array_map('trim', explode(",", $d)) as $v2) {
                                    $v3 = array_search($v2, $p['value']);
                                    if($v3) {
                                        $new2[$v3] = $v3;
                                    }
                                }
                            } else {
                                $new2 = array_search($d, $p['value']);
                            }
                            if($new2) {
                                $new[$p['id']] = $new2;
                            }
                        }
                    }
                }

                $basket = $this->get();
                $_new2 = array();
                if(isset($_basket[$basket])) {
                    foreach(array_map('trim', explode(";", $paramPrice)) as $v) {
                        $v = array_map('trim', explode(":", $v));
                        $price = cmfPrice::parse(get($v, 1));
                        $v = array_search($v[0], $_basket[$basket]);
                        if($v) {
                            $new[$basket][$v] = $v;
                            if($price) $_new2[$v] = $price;
                        }
                    }
                }
                $send['paramPrice'] = serialize($_new2);
                $send['param'] = serialize($new);
                $sql->add(db_product, $send, $id);
            } else {
                $section = $this->getSection($section, $row[1]);
            }
        }
        if($list) {
            $sql->placeholder("UPDATE ?t p SET p.isUpdate='yes', p.price=CEIL(IF(p.price1, p.price1, p.price2) * p.discount) WHERE p.id ?@", db_product, $list);
        }
        $this->saveLog($shop);
    }

    protected function getSection($section, $name) {
        $res = cRegister::sql()->placeholder("SELECT id, basket FROM ?t WHERE parent=? AND name=?", db_section, $section, $name)
                    ->fetchRow();
        if($res) {
            $this->push($res[0], $res[1]);
            return $res[0];
        } elseif($section) {
            return $this->getSection($this->pop(), $name);
        } else {
            return 0;
        }
    }


}

?>
