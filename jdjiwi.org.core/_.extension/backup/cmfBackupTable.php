<?php

class cmfBackupTable {

    const dump1 = 0;
    const dump2 = 1;
    const page = 2;

    public static function getFile($file) {
        switch ($file) {
            case self::dump1: return cDataPath . 'backup/site/dump_1.sql.gz';
            case self::dump2: return cDataPath . 'backup/site/dump_2.sql.gz';
            case self::page: return cDataPath . 'backup/pages/dump_1.sql.gz';
        }
        exit;
    }

    public static function optimize() {
        cRegister::sql()->optimize();
    }

    public static function exportDumpPages() {
        cDebug::sqlOff();
        cConfig::ignoreUserAbort();
        cConfig::timeLimit();

        $_table = array(db_admin_cache, db_pages_admin, db_pages_main, db_access_read, db_access_write);
        $_noData = array(db_admin_cache);
        cmfBackup::export(self::getFile(self::page), $_table, $_noData);

        cConfig::ignoreUserAbort(false);
        cLog::log('Экспорт завершен');
        cDebug::sqlOn();
    }

    public static function importDumpPages() {
        cDebug::sqlOff();
        cConfig::ignoreUserAbort();
        cConfig::timeLimit();

        cmfBackup::import(self::getFile(self::page));

        cConfig::ignoreUserAbort(false);
        cLog::log('Импорт завершен');
        cDebug::sqlOn();
    }

    public static function exportDump() {
        cDebug::sqlOff();
        cConfig::ignoreUserAbort();
        cConfig::timeLimit();

        $_table = cRegister::sql()->getTableList();
        unset($_table[db_admin_cache]);
        unset($_table[db_pages_admin]);
        unset($_table[db_pages_main]);
        unset($_table[db_access_read]);
        unset($_table[db_access_write]);

        $_noData = array(
            db_cache_data,
            db_cache_update);
        $_noData = array_combine($_noData, $_noData);

        foreach ($_table as $k => $v) {
            if (strpos($v, cDbPefix) !== 0) {
                unset($_table[$k]);
            }
        }

        $dump1 = self::getFile(self::dump1);
        $dump2 = self::getFile(self::dump2);
        if (is_file($dump1)) {
            if (is_file($dump2)) {
                unlink($dump2);
            }
            copy($dump1, $dump2);
        }
        cmfBackup::export($dump1, $_table, $_noData);

        cConfig::ignoreUserAbort(false);
        cLog::log('Экспорт завершен');
        cDebug::sqlOn();
    }

    public static function importDump() {
        cDebug::sqlOff();
        cConfig::ignoreUserAbort();
        cConfig::timeLimit();

        cmfBackup::import(self::getFile(self::dump1));

        cConfig::ignoreUserAbort(false);
        cLog::log('Импорт завершен');
        cDebug::sqlOn();
    }

}

?>
