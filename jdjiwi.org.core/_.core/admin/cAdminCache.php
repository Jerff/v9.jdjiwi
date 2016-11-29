<?php

class cAdminCache {

    static private function resurse() {
        return cRegister::sql();
    }

    //очистка кеша
    public function clear() {
        self::resurse()->truncate(db_admin_cache);
    }

    //функции хеширования
    static protected function hash($n) {
        return cHashing::crc32(cDomen . $n);
    }

    static protected function hash2($n) {
        return sha1(cDomen . $n);
    }

    // преобразуем теги для хранения в базе
    static private function reformTagSql($tag) {
        return $tag ? '[' . str_replace(',', '][', $tag) . ']' : '';
    }

    // кеширование данных
    // записываем в кеш
    public function set($n, $v, $tag = null, $time = 30) {
        self::resurse()->replace(db_admin_cache, array('id' => self::hash($n), 'name' => self::hash2($n), 'tag' => self::reformTagSql($tag), 'content' => serialize($v), 'time' => time() + 60 * $time));
    }

    // читаем из кеша
    public function get($n) {
        $res = self::resurse()->placeholder("SELECT name, content FROM ?t WHERE id=? AND `time`>=?", db_admin_cache, self::hash($n), time());
        $hash = self::hash2($n);
        while (list($n, $v) = $res->fetchRow()) {
            if ($hash === $n)
                break;
        }
        $res->free();
        return $v ? unserialize($v) : false;
    }

    // удаляем из кеша
    public function delete($n) {
        self::resurse()->del(db_admin_cache, array('id' => self::hash($n), 'AND', 'name' => self::hash2($n)));
    }

    public function deleteTag($n) {
        $where = '';
        foreach (explode(',', $n) as $k) {
            $where .= ($where ? ' OR ' : '') . "`tag` LIKE " . self::resurse()->quote("%[{$k}]%") . "";
        }
        self::resurse()->query("DELETE FROM " . db_admin_cache . " WHERE " . $where);
    }

    // кеш меню
    public function setMenu($n, &$v) {
        $name = cDomen . cPages::getMain() . cPages::getItem() . $n . cAdmin::user()->getGroupString();
        $this->set($name, $v, 'menu');
    }

    public function getMenu($n) {
        $name = cDomen . cPages::getMain() . cPages::getItem() . $n . cAdmin::user()->getGroupString();
        $this->get($name);
    }

}

?>