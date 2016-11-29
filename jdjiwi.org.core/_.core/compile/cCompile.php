<?php

cLoader::library('patterns/cPatternsStaticRegistry');
cLoader::library('compile/cCompileConfig');
cLoader::library('compile/cCompilePhp');
cLoader::library('compile/cCompileJsCss');
cLoader::library('compile/cCompileUpdate');

class cCompile extends cPatternsStaticRegistry {

    static public function is() {
        return cSettings::get('compilde', 'css&js') or 1;
    }

    static public function config() {
        return self::register('cCompileConfig');
    }

    static public function php() {
        return self::register('cCompilePhp');
    }

    static public function fileJsCss() {
        return self::register('cCompileJsCss');
    }

    static public function update() {
        return self::register('cCompileUpdate');
    }

}

?>