<?php

class cLog {

    private static $sqlTime = 0;
    private static $sqlCount = 0;
    private static $log = '';
    private static $startTime = null;

    /* инициализация */

    // инициализация работы
    static public function init() {
        self::$startTime = cSystem::microtime();
    }

    static public function destroy() {
        self::$log = null;
    }

    /* отладка memory */

    protected static function round($t) {
        return round($t, 3);
    }

    // количетсво потраченной пямяти
    static public function memory() {
        self::$log .= PHP_EOL . 'memory: ' . self::round(memory_get_usage() / 1024 / 1024);
    }

    /* ошибки error */

    private static function processingMessage(&$e) {
        if (is_a($e, 'Exception')) {
            $e = cException::parseTrace((string) $e);
        }
    }

    // добавить в лог ошибок php
    static public function error($message) {
        if (cDebug::isError()) {
            self::processingMessage($message);
//            if (!cDebug::isShutdown()) {
//                echo '<pre>' . $message . '</pre>';
//            }
            self::$log .= PHP_EOL . $message . PHP_EOL;
        }
    }

    // добавить в лог ошибок php
    static public function log($message) {
        if (cDebug::isError()) {
            self::processingMessage($message);
            self::$log .= PHP_EOL . $message;
        }
    }

    /* ошибки sql */

    // добавить в лог запросов к базе
    static public function sql($message = 'SELECT 1', $time = null) {
        if (!cDebug::isSql())
            return;
        $message = (++self::$sqlCount) . ' ' . self::round($time) . " " . cString::specialchars($message);
        if (class_exists('cPages')) {
            $page = cPages::getItem();
            if (cPages::isMain($page)) {
                $message .=" [{$page}]";
            } else {
                $main = cPages::getMain($page);
                $message .=" [{$main}] [{$page}]";
            }
        }
        self::$log .= PHP_EOL . $message;
        self::$sqlTime += $time;
    }

    // добавить в лог запросов к базе
    static public function explain($message) {
        if (cDebug::isExplain()) {
            self::$log .= PHP_EOL . cString::specialchars($message);
        }
    }

    /* лог */

    // показ лога
    static public function message() {
        $message = '<pre id="coreDebugLog">'
                . PHP_EOL . '<b>RUN/INIT</b> = ' . self::round(cSystem::microtime() - self::$startTime)
                . '/' . self::round(self::$startTime - cSystem::microtime(cTimeInit))
                . PHP_EOL . '<b>TIME</b> = ' . self::round(cSystem::microtime() - cSystem::microtime(cTimeInit));
        if (cDebug::isSql()) {
            $message .= PHP_EOL . '<b>SQL_TIME(' . self::$sqlCount . ')</b> = ' . self::round(self::$sqlTime);
        }
        return $message . self::$log . '</pre>';
    }

    // запись ошибок в файл
    static public function errorLog($message) {
        self::error($message);
        try {
            $message = PHP_EOL . date("Y-m-d H:i:s (T): ") . ' ' . $message;
            $dir = cDataPath . 'errorLog/' . date('Y-m') . '/';
            cDir::create($dir);
            $file = $dir . date('Y-m-d (H)') . '.log';
            cFile::isWritable($file);
            if (!($f = fopen($file, 'a'))) {
                throw new cFileException('запись не произведена', $file);
            }
            fwrite($f, strip_tags(trim($message)) . PHP_EOL . PHP_EOL . PHP_EOL);
            fclose($f);
        } catch (cException $e) {
            $e->error('Запись в лог не доступна');
        }
    }

}

?>