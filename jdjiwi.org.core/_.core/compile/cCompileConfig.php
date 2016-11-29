<?php

class cCompileConfig {

    //cmfCompilePhp
    public static function fileList() {
        return array(
            '.include.admin.php',
            '.include.ajax.php',
            '.include.application.php',
            '.include.application.system.php',
            '.include.cron.php',
            '.include.wysiwyng.php'
        );
    }

//    public static function includePath() {
//        return array(
//            cSoursePath,
//            cSoursePath . '_.config/',
//            cSoursePath . '_.core/',
//            cSoursePath . '_.extension/',
//            cSoursePath . '_.plugin/',
//            cSoursePath . '_library/'
//        );
//    }

    //cmfCompileFile
    public static function soursePath() {
        return cWWWPath . 'core-compile/';
    }

//
//    public static function sourseCss() {
//        return array(
//            'cssCompile.css',
//            array(
//                cWWWPath . 'css/',
//                cWWWPath . 'sourseCss/'
//            )
//        );
//    }
//
//    public static function sourseJs() {
//        return array(
//            'jsCompile.js',
//            array(
//                cWWWPath . 'sourseJs/',
//                cWWWPath . 'js/'
//            )
//        );
//    }
}

?>