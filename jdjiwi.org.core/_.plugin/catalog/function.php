<?php

cLoader::library('catalog/cmfPrice');
cLoader::library('catalog/cmfParam');
cLoader::library('catalog/cmfProductUrl');
cLoader::library('catalog/sort');
cLoader::library('catalog/filter');
function &cmfGetNoSection() {
	if(false===($no = cmfCache::get('cmfGetNoSection'))) {

		list($no1) = cRegister::sql()->placeholder("SELECT GROUP_CONCAT(id) FROM ?t WHERE isVisible='no'", db_section)
									->fetchRow();
		list($no2) = cRegister::sql()->placeholder("SELECT GROUP_CONCAT(id) FROM ?t WHERE visible='no'", db_brand)
									->fetchRow();
        $no = array($no1, $no2);
		cmfCache::set('cmfGetNoSection', $no, 'sectionList');
	}
	return $no;
}

function cmfGenerateSection(&$path, &$_section, $parent=0, $level=0) {
    if(!$parent and !isset($path[$parent])) return;
   	$sep = '';
   	for($i=0; $i<$level; $i++) {
       $sep .= '|- ';
   	};
   	foreach($path[$parent] as $id=>$v) {
   		$_section[$id] = $sep . $v;
   		if(isset($path[$id])) {
			cmfGenerateSection($path, $_section, $id, $level+1);
   		}
   	}
}

function cmfGenerateSectionArray(&$path, &$_section, $parent=0, $level=0) {
    if(!$parent and !isset($path[$parent])) return;
   	foreach($path[$parent] as $id=>$v) {
   		$v['level'] = $level;
   		$_section[$id] = $v;
   		if(isset($path[$id])) {
			cmfGenerateSectionArray($path, $_section, $id, $level+1);
   		}
   	}
}

?>