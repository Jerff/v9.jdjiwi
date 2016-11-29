<?php

cLoader::library('admin/cAdmin');
cLoader::library('admin/cAdminCache');
cLoader::library('register/cRegister');

set_include_path(
        get_include_path() .
        PATH_SEPARATOR . cAdminPath . '.core/' .
        PATH_SEPARATOR . cAdminPath . '.system/' .
        PATH_SEPARATOR . cAdminPath . '.templates/' .
        PATH_SEPARATOR . cAdminPath . 'modules/'
);

// авто загрузчик
function __autoload($name) {
    cAutoload::init($name);
}

class cAutoload {

    const path = '_.config|_.core|_.extension|_.plugin|_library';

    static private function getCache() {
        return cAdmin::cache()->get('__autoload');
    }

    static private function setCache(&$v) {
        cAdmin::cache()->set('__autoload', $v, 'load');
    }

    static private function delete() {
        cAdmin::cache()->delete('__autoload');
    }

    static public function init($name) {
        if (strpos($name, 'driver_') === 0) {
            $type = preg_replace('~(driver_)(controller|modul|db).*~', '$2', $name);
            switch ($type) {
                case 'controller':
                case 'modul':
                case 'db':
                    cLoader::file('_.' . $type . '/' . $name . '.php');
                    return;

                default :
                    break;
            }
        } else {
            $type = preg_replace('~^(.*)(controller|modul|db)$~', '$2', $name);
            switch ($type) {
                case 'controller':
                case 'modul':
                case 'db':
                    cLoader::modul($name);
                    return;
            }
        }

        if (strpos($name, 'view_') === 0) {
            cLoader::file('_.view/' . $name . '.php');
            return;
        }

        if (strpos($name, 'model_') !== false) {
            cLoader::file(preg_replace('~(model_)([^_]+)(.*)~', '$2/$1$2$3', $name) . '.php');
            return;
        }

        if (substr($name, -5) === '_core') {
            cLoader::file($name . '.php');
            return;
        }

        // автоматическая загрузка классов
        if (substr($name, 0, 3) === 'cmf' or substr($name, 0, 1) === 'c') {
            self::file($name);
        }
    }

    static private function file($name) {
        static $mClass = null;
        try {
            if (!$mClass)
                $mClass = self::getCache();

            if (!isset($mClass[$name]) or !is_file($mClass[$name])) {
                $mClass = array();
                foreach (explode('|', self::path) as $dir) {
                    self::getFile(cSoursePath . $dir . '/', $mClass);
                }
                self::setCache($mClass);
            }
            if (isset($mClass[$name])) {
                require_once($mClass[$name]);
            } else {
                throw new cException("ненайден класс {$name}");
            }
        } catch (cException $e) {
            $e->error();
        }
    }

    static private function getFile($dir, &$mClass) {
        if (!is_dir($dir))
            return;
        foreach (scandir($dir) as $file) {
            if ($file{0} === '.')
                continue;
            if (is_dir($dir . $file)) {
                self::getFile($dir . $file . '/', $mClass);
                continue;
            }
            if (preg_match('~cmf[A-Z][a-zA-z]+\.php~S', $file)) {
                $mClass[substr($file, 0, -4)] = $dir . $file;
            } elseif (preg_match('~c[A-Z][a-zA-z]+\.php~S', $file)) {
                $mClass[substr($file, 0, -4)] = $dir . $file;
            } elseif (preg_match('~[a-z\.0-9]+\.php~iS', $file)) {
                $mClass[substr($file, 0, -4)] = $dir . $file;
            }
        }
    }

}

?>