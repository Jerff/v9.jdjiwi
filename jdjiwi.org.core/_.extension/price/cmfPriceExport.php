<?php


class cmfPriceExport {

    protected $shop = 0;
    protected $row = 0;
    protected $sheet = null;
	protected $xml = null;

    public function __construct($shop, $uri) {
        $this->shop = $shop;

        cLoader::library('Spreadsheet/Excel/Writer');
        $this->xls = new Spreadsheet_Excel_Writer();
        $this->xls->setVersion(8);


        $sql = cRegister::sql();
        $this->sheet = $this->xls->addWorksheet('price');
        $this->sheet->setInputEncoding('utf-8');

        $center = $this->addFormat();
        $center->setAlign('center');

        $merge = $this->addFormat();
        $merge->setAlign('merge');

        $this->sheet()->setColumn(0, 5, 15);
        $this->write(0, 'Артикул', $center);
        $this->write(1, 'Производитель', $center);
        $this->write(2, 'Название', $center);
        $this->write(3, 'Цвет', $center);
        $this->write(4, 'Цена руб.', $center);
        $this->write(5, 'Скидка %', $center);

        $this->sheet()->setColumn(6, 6, 10);
        $this->write(6, 'Параметр корзины: Цена', $merge);
        $this->sheet()->setColumn(7, 7, 20);
        $this->write(7, '', $merge);

        $this->sheet()->setColumn(8, 8, 40);
        $this->write(8, 'Параметры', $center);

        $this->sheet()->setColumn(9, 10, 10);
        $this->write(9, 'Наличие', $center);
        $this->write(10, 'Видимый', $center);

        $tree = $sql->placeholder("SELECT parent, id, name, basket FROM ?t ORDER BY pos", db_section)
                        ->fetchAssocAll('parent', 'id');
        $this->exportSection($tree);

        $this->xls->send($uri .".xls");
        $this->xls->close();
        cDebug::destroy();
    }

	protected function exportSection($tree, $parent=0, $basket=0, $level=0) {
        if(!isset($tree[$parent])) return;
        $format = $this->addFormat();

        $format->setFontFamily('Helvetica');
        $format->setBold();
        $format->setSize(14-$level);
        $format->setAlign('merge');

        foreach($tree[$parent] as $id=>$v) {
            $this->newLine();
            $this->write(0, $v['name'], $format);
            for($i=1; $i<11; $i++) {
                $this->write($i, '', $format);
            }
            $item = $v['basket'] ? $v['basket'] : $basket;
            $this->exportSection($tree, $id, $item, $level+1);
            $this->exportProduct($id, $item);
        }
	}

	protected function param() {
        static $res = null;
        if($res) return $res;
        $sql = cRegister::sql();
        $param = $sql->placeholder("SELECT id, name, value FROM ?t ORDER BY name", db_param)
                     ->fetchAssocAll('id');
        foreach($param as $id=>$row) {
            $param[$id]['value'] = cConvert::unserialize($row['value']);
        }

        $color = $sql->placeholder("SELECT id, name FROM ?t ORDER BY name", db_color)
                     ->fetchRowAll(0, 1);
        return $res = array($param, $color);
    }
	protected function exportProduct($section, $basket) {
        $where = array('section'=>$section);
        if($this->shop) {
            $where[] = 'AND';
            $where['shop'] = $this->shop;
        }
        list($_param, $_color) = $this->param();

        $center = $this->addFormat();
        $center->setAlign('center');
        //$center->setVAlign('vcenter');

        $left = $this->addFormat();
        $left->setAlign('left');
        //$left->setVAlign('vcenter');

        $sql = cRegister::sql();
        $res = $sql->placeholder("SELECT p.*, b.name AS bName FROM ?t p LEFT JOIN ?t b ON(b.id=p.brand) WHERE ?w:p", db_product, db_brand, $where)
                        ->fetchAssocAll('id');
        foreach($res as $id=>$row) {
            $color = array();
            foreach(cConvert::pathToArray($row['color']) as $v) {
                $color[] = get($_color, $v);
            }
            $paramPrice = cConvert::unserialize($row['paramPrice']);
            $param = cConvert::unserialize($row['param']);
            if(isset($_param[$basket]) and $param) {
                $new = array();
                foreach($_param[$basket]['value'] as $k=>$v) {
                    if(isset($paramPrice[$k])) {
                        $new[] = $v .': '. $paramPrice[$k] .'р.';
                    } elseif(isset($param[$basket][$k])) {
                        $new[] = $v;
                    }
                }
                $paramPrice = $new;
            } else {
                $paramPrice = array();
            }

            $new = array();
            foreach($_param as $k=>$v) {
                if($k==$basket and $paramPrice) {
                    continue;
                }
                if(!isset($param[$k])) {
                    continue;
                }
                $new2 = array();
                if(is_array($param[$k])) {
                    foreach($v['value'] as $k2=>$v2) {
                        if(isset($param[$k][$k2])) {
                            $new2[] = $v2;
                        }
                    }
                } else {
                    if(isset($v['value'][$param[$k]])) {
                        $new2[] = $v['value'][$param[$k]];
                    }
                }
                if($new2) {
                    $new[] = $v['name'] .': '. implode(', ', $new2);
                }
            }
            $param = $new;
            $discount = 100-100*$row['discount'];

            $this->newLine();
            $this->setRow(15);
            $this->write(0, $row['articul'], $left);
            $this->write(1, $row['bName'], $left);
            $this->write(2, $row['name'], $left);
            $this->write(3, implode('; ', $color), $left);
            $this->write(4, $row['price1'], $center);
            $this->write(5, $discount ? $discount : 'нет', $center);
            if($paramPrice) {
                $this->write(6, get2($_param, $basket, 'name'), $center);
                $this->write(7, implode(";\n", $paramPrice), $left);
            }
            $this->write(8, implode(";\n", $param), $left);
            $this->write(9, $row['dump']==='yes' ? 'да' : 'нет', $center);
            $this->write(10, $row['visible']==='yes' ? 'да' : 'нет', $center);
        }
	}

    protected function setRow($n) {
        $this->sheet->setRow($this->row, $n);
    }
    protected function sheet() {
        return $this->sheet;
    }
    protected function newLine() {
        $this->row++;
    }
    protected function write($col, $token, $format=null) {
        $this->sheet->write($this->row, $col, $token, $format);
    }
    protected function addFormat() {
        return $this->xls->addFormat();
    }


}

?>
