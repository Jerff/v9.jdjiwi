<?php

cLoader::library('system/cSystem');
cLoader::library('system/cFilter');
cLoader::library('system/cString');
cLoader::library('system/cConvert');
cLoader::library('system/cJScript');
cLoader::library('system/cHashing');
cLoader::library('system/cMatch');

function get($v, $k, $d = null) {
    if (isset($v[$k]))
        return $v[$k];
    else
        return $d;
}

function get2($v, $k, $k2, $d = null) {
    if (isset($v[$k][$k2]))
        return $v[$k][$k2];
    else
        return $d;
}

function get3($v, $k, $k2, $k3, $d = null) {
    if (isset($v[$k][$k2][$k3]))
        return $v[$k][$k2][$k3];
    else
        return $d;
}

function cRedirect($u) {
    header('Location: ' . $u);
    exit;
}

function cRedirectSeo($u) {
    header('HTTP/1.1 301 Moved Permanently: ' . $u);
    cRedirect($u);
}

?>