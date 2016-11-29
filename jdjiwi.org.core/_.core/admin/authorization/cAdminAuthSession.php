<?php

class cAdminAuthSession {

    private $id = null;
    private $session = null;

    public function __construct($auth, $name) {
        $this->setName($name);
        if (!$id = (int) cCookie::get($name))
            return;
        $this->setId($id);
        if (!$ses = cSession::get($name))
            return;
        if (get2($ses, 'data', 'debugError') === 'yes')
            return;
        if (get($ses, 'id') == $id) {
            if (isset($ses['session'])) {
                $this->setData($ses['session']);
            }
            if (isset($ses['data'])) {
                $auth->setData($ses['data']);
            }
        }
    }

    // имя сессии
    protected function setName($name) {
        $this->name = $name;
    }

    protected function getName() {
        return $this->name;
    }

    // id пользователя
    public function setId($id) {
        $this->id = (int) $id;
    }

    public function getId() {
        return $this->id;
    }

    // данные сессии
    private function setData($session) {
        $this->session = $session;
    }

    private function getData() {
        return $this->session;
    }

    // таблица данных
    protected function getDb() {
        return db_user_ses;
    }

    // проверка авторизации
    public function isAuth() {
        $session = $this->getData();

        if ($session) {
            if ($session['ip'] !== cInput::ip()->getInt() or $session['proxy'] !== cInput::ip()->proxyInt()) {
                $this->remove();
                return false;
            }
            if (($session['date'] + 2 * 60) < time()) {
                $this->update();
                cRegister::sql()->replace($this->getDb(), array('sesDate' => date('Y-m-d H:i:s')), $this->getId());
            }
            return true;
        }

        $is = cRegister::sql()->placeholder("SELECT 1 FROM ?t WHERE `id`=? AND IF(`isIp`='yes', `sesIp`=? AND `sesProxy`=?, 1)", $this->getDb(), $this->getId(), cInput::ip()->getInt(), cInput::ip()->proxyInt())
                ->numRows();
        if (!$is) {
            $this->remove();
            return false;
        }
        return true;
    }

    // инициализация сессии
    public function init($row) {
        $this->update();
        cSession::set($this->getName(), array('id' => $this->getId(),
            'data' => $row,
            'session' => array('ip' => cInput::ip()->getInt(), 'ip' => cInput::ip()->getInt(), 'proxy' => cInput::ip()->proxyInt(), 'date' => time())));
        cRegister::sql()->replace($this->getDb(), array('id' => $this->getId(), 'isIp' => $row['isIp'], 'sesIp' => cInput::ip()->getInt(), 'sesProxy' => cInput::ip()->proxyInt(), 'sesDate' => date('Y-m-d H:i:s')));
    }

    // продление сессии
    public function update() {
        cCookie::set($this->getName(), $this->getId(), 12);
        cSession::set($this->getName(), 'session', 'date', time());
    }

    // удаление сессии
    public function remove() {
        $this->setId(0);
        cCookie::del($this->getName());
        cSession::del($this->getName());
        cRegister::sql()->del($this->getDb(), $this->getId());
    }

    // разлогивание
    public function logOut() {
        $this->remove();
        cRegister::sql()->del($this->getDb(), $this->getId());
    }

}

?>