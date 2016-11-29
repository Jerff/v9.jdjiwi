<?php

class cDir {

    // проверка папки
    static public function is($folder) {
        return is_dir($folder);
    }

    // имя каталога
    static public function name($folder) {
        return dirname($folder);
    }

    // сменить права
    static public function chmod($folder, $mode = cDirMode) {
        if (!chmod($folder, $mode)) {
            throw new cFileException('права файла не изменены', array($folder, $mode));
        }
    }

    // создание папки
    static public function create($path, $mode = cDirMode) {
//        try {
        if (self::is($path)) {
            return true;
        }
        if (!$is = mkdir($path, $mode, true)) {
            throw new cFileException('Невозможно создать папку', $path);
        }
        self::chmod($name, $mode);
//        return $is;
//        } catch (cFileException $e) {
//            $e->errorLog();
//        }
    }

    static public function clear($folder, $del = false) {
        if (!self::is($folder))
            return;
        foreach (scandir($folder) as $file) {
            if (self::is($folder . $file) and $file{0} !== '.') {
                self::clear($folder . $file . '/');
                rmdir($folder . $file . '/');
            } else {
                if (cFile::is($folder . $file) and $file{0} !== '.') {
                    unlink($folder . $file);
                }
            }
        }
        sleep(1);
        if ($del) {
            rmdir($folder);
        }
    }

    static public function getFiles($folder) {
        $result = array();
        if (!self::is($folder)) {
            return $result;
        }
        foreach (scandir($folder) as $file) {
            if (cFile::is($folder . $file) and $file{0} !== '.') {
                $result[] = $folder . $file;
            }
        }
        return $result;
    }

    static public function getFolders($folder) {
        $result = array();
        if (!self::is($folder)) {
            return $result;
        }
        foreach (scandir($folder) as $file) {
            if (is_dir($folder . $file) and $file{0} !== '.') {
                $result[] = $folder . $file . '/';
            }
        }
        return $result;
    }

}

?>