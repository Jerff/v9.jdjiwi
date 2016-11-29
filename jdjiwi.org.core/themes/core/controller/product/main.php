<?php

$sql = cRegister::sql();
$sectionId = cGlobal::get('$sectionId');
$main = $sql->placeholder("SELECT name, image, width, height FROM ?t WHERE id=? AND visible='yes' ORDER BY pos", db_section, $sectionId)
            ->fetchAssoc();
if(!$main) return 404;
$main['title'] = cString::specialchars($main['name']);
$main['image'] = cBaseImgUrl . path_catalog . $main['image'];
$this->assing('main', $main);


$small = $sql->placeholder("SELECT id, name, IF(`type`='edit', url, catalogUrl) AS url, `top`, `left`, `height`, `width` FROM ?t WHERE parent=? AND visible='yes' ORDER BY pos", db_section_shop, $sectionId)
            ->fetchAssocAll('id');
foreach($small as $k=>$v) {
    $small[$k]['title'] = cString::specialchars($v['name']);
}
$this->assing('small', $small);

?>