<?php

class cFile {

    /* === копирование файлов === */

    static function copy($file, $newFile) {
        try {
            $folder = cDir::name($newFile);
            cDir::create($folder);
            cFile::isWritable($file);
            $name = $newFile;
            while (file_exists($name)) {
                if (strpos($file, '.')) {
                    $name = preg_replace('`(.*)\.([^.]*)$`', '$1.' . rand(0, 9999) . '.$2', $newFile);
                } else {
                    $name = $newFile . rand(0, 9999);
                }
            }
            if (copy($file, $name)) {
                throw new cFileException('файл не скопирован', array($file, $name));
            }
            self::chmod($name);
            return $name;
        } catch (cFileException $e) {
            $e->errorLog('Невозможно скопировать файл');
        }
        return false;
    }

    /* === /копирование файлов === */



    /* === файловые фанкции === */

    // проверка файла
    static public function is($file) {
        return is_file($file);
    }

    // переименовать файл
    static function rename($file, $newFile) {
        return rename($file, $newFile);
    }

    // сменить права
    static public function chmod($file, $mode = cFileMode) {
        if (!chmod($file, $mode)) {
            throw new cFileException('права файла не изменены', array($file, $mode));
        }
    }

    // удалить файл
    static public function unlink($file) {
        if (is_file($file)) {
            unlink($file);
        }
    }

    /* === /файловые фанкции === */

    // проверка на достпуность записи
    static public function isWritable($file) {
//        try {
        if (file_exists($file)) {
            if (!is_writable($file)) {
                throw new cFileException('Невозможна запись в файл', $file);
            }
        } else {
            if (!is_writable(dirname($file))) {
                throw new cFileException('Невозможно создать файл в папке', dirname($file));
            }
        }
        return true;
//        } catch (cFileException $e) {
//            $e->errorLog('34');
//        }
    }

    static public function curl($url, $post) {
        $page = parse_url($url, PHP_URL_PATH);
        $headers = array("POST " . $page . " HTTP/1.0");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

}

?>