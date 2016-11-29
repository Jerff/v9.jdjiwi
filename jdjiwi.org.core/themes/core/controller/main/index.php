<?php

$sql = cRegister::sql();
$main = $sql->placeholder("SELECT id, name, image FROM ?t WHERE visible='yes' ORDER BY pos", db_showcase)
        ->fetchAssocAll('id');
foreach ($main as $k => $v) {
    $main[$k]['title'] = cString::specialchars($v['name']);
    $main[$k]['image'] = cBaseImgUrl . path_showcase . $v['image'];
}
$this->assing('main', $main);

$small = $sql->placeholder("SELECT parent, id, name, IF(`type`='edit', url, catalogUrl) AS url, `top`, `left`, `height`, `width` FROM ?t WHERE parent ?@ AND visible='yes'", db_showcase_list, array_keys($main))
        ->fetchAssocAll('parent', 'id');
foreach ($small as $p => $list) {
    foreach ($list as $k => $v) {
        $small[$p][$k]['title'] = cString::specialchars($v['name']);
    }
}
$this->assing('small', $small);

if (cSettings::get('showcase', 'isAnimation')) {
    $this->assing2('animationTime', cSettings::get('showcase', 'animationTime'));
}
?>