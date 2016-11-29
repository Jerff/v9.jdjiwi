<?php

cLoader::library('sql/cmfPDO');

abstract class cmfSqlDriver {

    // управление ресурсом
    private $res = null;

    protected function set(&$res) {
        $this->res = $res;
    }

    protected function get() {
        return $this->res;
    }

    public function query($query) {
        try {
            if (cDebug::isSql()) {
                $t = cSystem::microtime();
            }
            if ($res = $this->get()->query($query)) {
                if (cDebug::isSql()) {
                    cLog::sql(trim($query), cSystem::microtime() - $t);
                    if (cDebug::isExplain() and (stripos($query, 'SELECT') !== false)) {
                        $exp = $this->get()->query('EXPLAIN ' . $query)->fetchAssocAll();
                        $query = '<b>EXPLAIN</b> ' . $query;
                        foreach ($exp as $r) {
                            $query .= "\n" . print_r($r, true);
                        }
                        cLog::explain($query);
                    }
                }
            } else {
                throw new cSqlException($query, $this->get()->errorInfo());
            }
        } catch (cSqlException $e) {
            $e->errorLog();
        }
        return $res;
    }

    public function getAttribute($attr) {
        return $this->get()->getAttribute($attr);
    }

    public function lastInsertId() {
        return $this->get()->lastInsertId();
    }

    public function getClientVersion() {
        return $this->getAttribute(PDO::ATTR_CLIENT_VERSION);
    }

    # управление записями
    // добавлние

    function add($table, $data, $id = 0) {
        if (is_array($id)) {
            if (sizeof($id) > 1)
                return $this->add2($table, $data, $id);
            $key = key($id);
            $id = current($id);
        } else
            $key = 'id';
        if ($id) {
            $this->placeholder("UPDATE ?t SET ?% WHERE ?w", $table, $data, array($key => $id));
            return $id;
        } else {
            $this->placeholder("INSERT INTO ?t SET ?%", $table, $data);
            return $this->lastInsertId();
        }
    }

    // добавлние для составных ключей
    function add2($table, &$data, $where) {
        if ($this->placeholder("SELECT 1 FROM ?t WHERE ?w", $table, $where)->numRows()) {
            $this->placeholder("UPDATE ?t SET ?% WHERE ?w", $table, $data, $where);
            return null;
        } else {
            while (list($k, $v) = each($where)) {
                if (is_string($k)) {
                    $data[$k] = $v;
                }
            }
            $this->placeholder("INSERT INTO ?t SET ?%", $table, $data);
            return $this->lastInsertId();
        }
    }

    // обвновление записи
    function update($table, $data, $where) {
        if (!is_array($where))
            $where = array('id' => $where);
        return $this->placeholder("UPDATE ?t SET ?% WHERE ?w", $table, $data, $where);
    }

    // запись в базу
    function replace($table, $where) {
        return $this->placeholder("REPLACE INTO ?t SET ?%", $table, $where);
    }

    // удаление записи
    function del($table, $where) {
        if (!is_array($where))
            $where = array('id' => $where);
        return $this->placeholder("DELETE FROM ?t WHERE ?w", $table, $where);
    }

    # плацехолдеры
    // типы экранирования

    public function quote($str) {
        return $this->get()->quote($str);
    }

    public function quoteString($str) {
        return $str === null ? 'NULL' : $this->quote($str);
    }

    public function quoteParam($str) {
        return '`' . $str . '`';
    }

    // вызовы плацехолдера
    public function placeholder() {
        return $this->query($this->_placeholder(func_get_args()));
    }

    public function getQuery() {
        return $this->_placeholder(func_get_args());
    }

    private $args = null;
    private $arg = null;

    private function setArgs(&$a) {
        $this->arg = 0;
        $this->args = $a;
    }

    private function &getArgs() {
        return $this->args[$this->arg++];
    }

    private function delArgs() {
        $this->args = null;
    }

    private function _placeholder($args) {
        $query = (string) array_shift($args);

        $this->setArgs($args);
        $query = preg_replace_callback('~\?([^\:\s]*)\:([^\s\,\'\"]*)|\?(fields|field|function|t\%|[^\s\,\'\"\)]?)~S', array(&$this, '_placeholder_run'), $query);
        $this->delArgs();
        return $query;
    }

    private function _placeholder_run($m) {
        if (isset($m[3])) {
            $c = $m[3];
            $t = '';
        } else {
            $c = $m[1];
            $t = $m[2] . '.';
        }
        unset($m);

        $a = $this->getArgs();
        switch ($c) {
            case 'f':
            case 'function':
                $str = '';
                $sep = '';
                while (list($k, $v) = each($a)) {
                    if (is_string($k))
                        $str .= $sep . $k . ' AS ' . $v;
                    else
                        $str .= $sep . $v;
                    $sep = ', ';
                }
                return $str;

            case 'fields':
                $str = '';
                $sep = '';
                while (list($k, $v) = each($a)) {
                    if (is_string($k))
                        $str .= $sep . $t . $this->quoteParam($k) . ' AS ' . $v;
                    else
                        $str .= $sep . $t . $this->quoteParam($v);
                    $sep = ', ';
                }
                return $str;

            case 'field':
                return $this->quoteParam($a);

            case 's':
                return addslashes($a);

            case 'like':
                return '%' . addslashes(str_replace('%', '%%', $a)) . '%';

            case 'i':
                return (int) $a;

            case 't':
                return $this->quoteParam($a);

            case 't%':
                $str = '';
                $sep = '';
                while (list($k, $v) = each($a)) {
                    $str .= $sep . $this->quoteParam($v);
                    $sep = ', ';
                }
                return $str;


            case '@':
                if (!count($a))
                    return 'IN(-1)';
                $str = '';
                $sep = '';
                while (list($k, $v) = each($a)) {
                    $str .= $sep . $this->quoteString($v);
                    $sep = ', ';
                }
                return 'IN(' . $str . ')';

            case '%':
                $str = '';
                $sep = '';
                while (list($k, $v) = each($a)) {
                    $str .= $sep . $this->quoteParam($k) . '=' . $this->quoteString($v);
                    $sep = ', ';
                }
                return $str;

            case 'w':
                while (list($k, $v) = each($a))
                    if (is_array($v)) {
                        if (count($v)) {
                            while (list($k2, $v2) = each($v))
                                $v[$k2] = $this->quoteString($v2);
                            $v = 'IN(' . implode(', ', $v) . ')';
                        } else
                            $v = 'IN(-1)';

                        $a[$k] = $t . $this->quoteParam($k) . ' ' . $v;
                    } elseif (is_string($k))
                        $a[$k] = $t . $this->quoteParam($k) . '=' . $this->quoteString($v);
                    else
                        $a[$k] = (string) $v;

                return implode(' ', $a);

            case 'o':
                while (list($k, $v) = each($a)) {
                    if ($k === 'function') {
                        $a[$k] = $v;
                    } else
                    if (is_string($k))
                        $a[$k] = $t . $k . ' ' . $v;
                    else
                        $a[$k] = $t . $this->quoteParam($v);
                }
                return implode(', ', $a);


            default:
                return $this->quoteString($a);
        }
    }

    # создание и выполнение заранее известных команд
    // количество рядов

    public function getFoundRows() {
        $row = $this->query('SELECT FOUND_ROWS()')->fetchRow(0);
        return (int) $row;
    }

    // список все таблиц базы
    public function getTableList() {
        $res = $this->query("SHOW TABLES")->fetchRowAll();
        $_table = array();
        while (list(, list($row)) = each($res)) {
            $_table[$row] = $row;
        }
        return $_table;
    }

    // очищение таблиц базы
    public function truncate() {
        foreach (func_get_args() as $t) {
            $this->placeholder("TRUNCATE TABLE ?t", $t);
        }
    }

    // оптимизация таблиц базы
    public function optimize() {
        if (func_num_args()) {
            $table = array();
            foreach (func_get_args() as $value) {
                if (is_string($value)) {
                    $table[] = $value;
                } else {
                    $table = array_merge($table, (array) $value);
                }
            }
        } else {
            $table = $this->getTableList();
        }
        $this->placeholder("OPTIMIZE TABLE ?t%", $table);
    }

}

?>
