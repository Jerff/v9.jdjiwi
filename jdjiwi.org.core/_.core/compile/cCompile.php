<?php

cLoader::library('patterns/cPatternsStaticRegistry');
cLoader::library('compile/cCompileConfig');
cLoader::library('compile/cCompilePhp');
cLoader::library('compile/cCompileFile');
cLoader::library('compile/cCompileUpdate');

class cCompile extends cPatternsStaticRegistry {

    static public function path($type, $file) {
        if (isComplile < 2) {
            return $file;
        }
        $compile = cCompilePath . $type . '/' . urlencode($file) . '.php';
        if (file_exists($compile)) {
            if (isComplile == 3)
                return $compile;
            else {
                if (filemtime($file) < filemtime($compile))
                    return $compile;
            }
        }
        file_put_contents($compile, cCompile::php()->compile(cAppPathController . $p58 . '.php', true));
        return $compile;
    }

    static public function config() {
        return self::register('cCompileConfig');
    }

    static public function php() {
        return self::register('cCompilePhp');
    }

    static public function file() {
        return self::register('cCompileFile');
    }

    static public function update() {
        return self::register('cCompileUpdate');
    }

}

?>