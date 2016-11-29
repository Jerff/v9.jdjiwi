<?php

class cmfBackupSite {

    public static function getFile($file = '') {
        return cDataPath . 'backup/data/' . $file;
    }

    public static function getFileList() {
        $modul = cmfBackupConfig::menu();
        $dump = cRegister::sql()->placeholder("SELECT id, name FROM ?t", db_backup_site)
                ->fetchRowAll(0, 1);

        $_file = $res = array();
        foreach (cDir::getFiles(self::getFile()) as $file) {
            $id = (int) preg_replace('~^\[([0-9]+)\](.*)~', '$1', $file);
            preg_match_all('~\[([a-z]+)\]~i', $file, $list);
            $list = $list[1];
            $name = get($dump, $id);
            $date = preg_replace('~^(.*\]\s)(.*)(\s+\[.*)$~U', '$2', $file);
            foreach ($list as $v) {
                $name = str_replace("[{$v}]", '[' . get($modul, $v) . ']', $name);
            }
            $_file[$file] = array('name' => "[{$date}] {$name}",
                'date' => $date,
                'file' => $file,
                'modul' => array());
            foreach ($list as $v) {
                $_file[$file]['modul'][$v] = get($modul, $v, $v);
            }
        }
        uasort($_file, array('cmfBackupSite', 'sort'));
        return $_file;
    }

    public static function sort($a, $b) {
        if ($a['date'] == $b['date']) {
            return 0;
        }
        return ($a['date'] > $b['date']) ? -1 : 1;
    }

    public static function import($file, $list) {
        cDebug::sqlOff();
        cConfig::ignoreUserAbort();
        cConfig::timeLimit();

        cmfBackup::setBlok($list);
        cmfBackup::import(self::getFile($file));

        cConfig::ignoreUserAbort(false);
        cLog::log('Импорт завершен');
        cDebug::sqlOn();
    }

}

?>