<?php

class cApplication {
    /* инициализация работы */

    // инициализация работы
    public static function start() {
        cLog::init();
        $func = 'init' . cString::firstToUpper(cApplication);
        return static::$func();
    }

    /* application */

    // авторизация приложения
    static protected function authorization() {
        if (cAdmin::user()->authorization()) {
            if (cAdmin::user()->debugError === 'yes')
                cDebug::setError();
            if (cAdmin::user()->debugSql === 'yes')
                cDebug::setSql();
            if (cAdmin::user()->debugExplain === 'yes')
                cDebug::setExplain();
            //if(cRegister::getAdmin()->debugCache==='yes')	cmfCache::setPages();
            //cmfCache::setData(cRegister::getAdmin()->debugCache==='yes');
        } else {
//            echo 'Для просмотра нужна авторизация';
//            exit;
        }
    }

    // инициализация приложения
    static protected function initApplication() {
        self::authorization();

        cLog::memory();
        $controler = new cmfApplicationTemplate();
        echo $controler->main();
        cLog::memory();
    }

    /* admin */

    // инициализация админ панели
    static protected function initAdmin() {
        if (cAjax::is()) {
            if (!cAdmin::user()->authorization()) {
                cPages::setMain('/admin/enter/');
            } else {
                if (cPages::isMain('/admin/index/') or cPages::isMain('/admin/enter/')) {
                    cAjax::get()->redirect(cBaseAdminUrl);
                }
                if (cAjax::isCommand('exit')) {
                    cAdmin::user()->logOut();
                    cAjax::get()->alert('Выход из системы')
                            ->reload();
                }
                if (cAdmin::user()->debugError === 'yes')
                    cDebug::setError();
                if (cAdmin::user()->debugSql === 'yes')
                    cDebug::setSql();
//        if ($admin->debugExplain === 'yes')
//            cDebug::setExplain();
            }
        }
        cConfig::timeLimit();
        cConfig::ignoreUserAbort();

        cLog::memory();
        cAdmin::template()->start();
        cLog::memory();
    }

    /* ajax */

    // инициализация ajax
    static protected function initAjax() {
        self::authorization();
        ini_set('html_errors', 'Off');

        cCallAjax::start();
    }

    /* cron */

    // инициализация cron
    static protected function initCron() {
        self::authorization();
        cConfig::sessionClose();
        cConfig::timeLimit();
        cConfig::ignoreUserAbort();

        cCallCron::start();
    }

    /* wysiwyng */

    // инициализация wysiwyng
    static protected function initWysiwyng() {
        if (!cAdmin::user()->is()) {
            exit;
        }
        cConfig::timeLimit();
        return cCallWysiwyng::start();
    }

}

?>