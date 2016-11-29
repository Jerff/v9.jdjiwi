<?php


//$r = cRegister::request();


$param = cInput::post()->get('param');
if($param and is_array($param)) {
	$sectionId = (int)cInput::post()->get('section');

	if(cInput::post()->get('paramMainNew')) {
	    if(false===($param = cmfCache::get('cmfSectionFilter Param'. $sectionId))) {
	    	$res = cRegister::sql()->placeholder("SELECT id FROM ?t WHERE types=(SELECT type FROM ?t WHERE id=?) AND `select`='yes' AND visible='yes' ORDER BY pos", db_param, db_section, $sectionId)
										->fetchAssocAll();
			$query = $sep = '';
			foreach($res as $row) {
	            $param[$row['id']] = 0;
			}
	        cmfCache::set('cmfSectionFilter Param'. $sectionId, $param, array('paramList', 'section'. $sectionId));
		}
	}
	$brandId = array();
	$brand = cInput::post()->get('brand');
	if($brand and is_array($brand))
		foreach($brand as $k=>$v) {
			$brandId[(int)$k] = (int)$k;
		}

	$paramMainId = (int)cInput::post()->get('paramMainId');
	$paramMainValue = (int)cInput::post()->get('paramMainValue');

	list($brand, $paramSearch) = cmfSectionFilter($sectionId, $brandId, $paramMainId, $paramMainValue, $param);
} else exit;

foreach($paramSearch as $id=>$v) {
	ob_start(); ?>
<?=$v['name'] ?>
<div class="clearFloat"></div>
<select name="param[<?=$id ?>]" id="param[<?=$id ?>]" onchange="cmfSectionFilterShange(this.form)">
<option></option>
<?  $k = (int)get($param, $id);
	foreach($v['param'] as $k2=>$v2) { ?>
<option value="<?=$k2 ?>" <?=$k==$k2 ? 'selected' : '' ?>><?=$v2 ?></option>
<? } ?>
</select>
<?
	$content = ob_get_clean();
	cAjax::get()->html('#select'. $id, $content);
}

?>