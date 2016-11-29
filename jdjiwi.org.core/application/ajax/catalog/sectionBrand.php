<?php



//$r = cRegister::request();

cLoader::library('catalog/function');
$sectionId = cInput::post()->get('section');

if(false===($_brand = cmfCache::get('sectionBrand'. $sectionId))) {

	if($sectionId) {
		$section = cRegister::sql()->placeholder("SELECT path FROM ?t WHERE id=? AND isMenu='yes' AND isVisible='yes'", db_section, $sectionId)
										->fetchAssoc();
		$_brand = cRegister::sql()->placeholder("SELECT id, name FROM ?t WHERE id IN(SELECT brand FROM ?t WHERE section IN(SELECT id FROM ?t WHERE id=? OR path LIKE '?s%' AND isMenu='yes' AND isVisible='yes') AND visible='yes' GROUP BY brand) AND visible='yes' ORDER BY `pos`", db_brand, db_product, db_section, $sectionId, $section['path'] ."[$sectionId]")
										->fetchRowAll(0, 1);

	} else {
		$section = array();
		$_brand = cRegister::sql()->placeholder("SELECT id, name FROM ?t WHERE id IN(SELECT brand FROM ?t WHERE section IN(SELECT id FROM ?t WHERE isMenu='yes' AND isVisible='yes') AND visible='yes' GROUP BY brand) AND visible='yes' ORDER BY `pos`", db_brand, db_product, db_section)
										->fetchRowAll(0, 1);
	}
	cmfCache::set('sectionBrand'. $sectionId, $_brand, array('section'. $sectionId));
}

ob_start();
foreach($_brand as $k=>$v) {
	?><div class="check fastSearch1 cHide brandCheckbox"><label><input type="checkbox" id="brand1[<?=$k ?>]" name="brand1[<?=$k ?>]" value="<?=$k ?>" onclick="cmf.getId('checkBrandAll').checked=false;"><?=$v ?></label></div><?
}
$content = cJScript::quote(ob_get_clean());


if(cInput::post()->get('main')) {
    cAjax::get()->script("
$('.brandCheckbox', $('#mainSearchText')).remove();
$('.brandCheckboxAll').before('$content');
cmfSelectBrandAll();
cmfShowSearch();
    ");

} else {
	ob_start();
	?>Производитель
<select name="brand"><option></option>
<? foreach($_brand as $k=>$v) { ?>
<option value="<?=$k ?>"><?=$v ?></option>
<? } ?>
</select>
<script type="text/javascript">
$('.brandCheckbox', $('.checkBrand2')).remove();
$(".brandCheckboxAll").before("<?=$content ?>");
cmfSelectBrandAll();
if(!cmfIsHide($('.brandCheckboxAll', $('.checkBrand2')))) {
	cmfShowSearch();
}
</script>
<?
	cAjax::get()->html('#brandList1', ob_get_clean());

}

?>