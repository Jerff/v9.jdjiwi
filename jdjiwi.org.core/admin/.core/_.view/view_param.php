<?php


class view_param {


	public static function paramView($jsModul, $form, $paramList, $isBasket) {
		$basket1 = $isBasket ? 'class="cHide"' : '';
        $basket2 = $isBasket ? '' : 'class="cHide"';
        foreach($paramList as $key=>$value) {
			$class = ($basket = ($value['type']==='basket')) ? $basket2 : $basket1;
			switch($type = $value['type']) {
				case 'radio':
?>
	<tr id="param<?=$key ?>" <?=$class ?>>
	<td class="svet_nane_td" width="15%"><?=$value['name'] ?>:</td>
	<td class="svet_td2 selectParamView" colspan="2">
        <div id="selectParamView<?=$key ?>" class="cursor paramEditCheckbox width25">Показать свойства</div>
	    <div class="paramEdit paramEdit<?=$key ?>"><?=cmfAdminView::onclickType1("if(confirm('Удалить выбранное значение?')) {$jsModul}.postAjax('paramDel', $key);", '[удалить выбранное значение]') ?></div>
	    <div class="paramEdit<?=$key ?>">
    	    <div class="paramMiddle"><input name="newId<?=$key ?>" id="newId<?=$key ?>" type="text"></div>
    	    <div class="paramEdit paramMiddle"><?=cmfAdminView::onclickType1("{$jsModul}.postAjax('paramAdd', $key, 'newId{$key}');", 'Добавить'); ?> </div>
    	</div>
        <script language="JavaScript">
        cmf.admin.param.hideShow('<?=$key ?>');
        </script>

	    <span class="selectCheckbox"><?=$form->html($key, $type==='select'? 'class="width50"' : null); ?></span>
	</td>
	</tr>
<?
					break;

				case 'select':
?>
	<tr id="param<?=$key ?>" <?=$class ?>>
	<td class="svet_nane_td" width="15%"><?=$value['name'] ?>:</td>
	<td class="svet_td2 selectCheckbox"><?=$form->html($key, $type==='select'? 'class="width99"' : null); ?></td>
	<td class="svet_td2 selectParamView">
	    <div id="selectParamView<?=$key ?>" class="cursor  ">Показать свойства</div>
	    <div class="paramEdit paramEdit<?=$key ?>"><?=cmfAdminView::onclickType1("if(confirm('Удалить выбранное значение?')) {$jsModul}.postAjax('paramDel', $key);", '[удалить выбранное значение]') ?></div>
	    <div class="paramEdit<?=$key ?>">
    	    <div class="paramMiddle"><input name="newId<?=$key ?>" id="newId<?=$key ?>" type="text"></div>
    	    <div class="paramEdit paramMiddle"><?=cmfAdminView::onclickType1("{$jsModul}.postAjax('paramAdd', $key, 'newId{$key}');", 'Добавить'); ?> </div>
    	</div>
        <script language="JavaScript">
        cmf.admin.param.hideShow('<?=$key ?>');
        </script>
	</td>
	</tr>
<?
					break;

				case 'checkbox':
?>
	<tr id="param<?=$key ?>" <?=$class ?>>
	<td class="svet_td" width="15%"><?=$value['name'] ?>:</td>
	<td class="svet_td2 selectParamView" colspan="2">

	    <div id="selectParamView<?=$key ?>" class="cursor paramEditCheckbox width25">Показать свойства</div>
    	<div class="paramEdit<?=$key ?>">
        	<div class="paramMiddle checkboxDelete"><select name="delete<?=$key ?>" class="width99">
            		<option value="-1">Не выбрано</option>
            		<? foreach($value['key'] as $key2=>$key3) { ?>
            		<option value="<?=$key2 ?>"><?=$value['value'][$key2] ?></option>
            		<? } ?>
        		</select></div>
    	    <div class="paramEdit paramMiddle"><?=cmfAdminView::onclickType1("if(confirm('Удалить?')) {$jsModul}.postAjax('paramDelChecbox', $key);", '[удалить]') ?></div>
        </div>
	    <div class="paramEdit<?=$key ?>">
    	    <div class="paramMiddle"><input name="newId<?=$key ?>" id="newId<?=$key ?>" type="text"></div>
    	    <div class="paramEdit paramMiddle"><?=cmfAdminView::onclickType1("{$jsModul}.postAjax('paramAdd', $key, 'newId{$key}');", 'Добавить'); ?> </div>
    	</div>
        <script language="JavaScript">
        cmf.admin.param.hideShow('<?=$key ?>');
        </script>

        <span class="selectCheckbox">
	    <? foreach($value['key'] as $key2=>$key3) {
	        if($basket) {
                echo "<span>". $form->html($value['price'][$key2], 'class="width50 paramPrice"') .'&nbsp;'. $form->html($key3) .'</span>';
	        } else {
    	        echo $form->html($key3);
	        }
	    }?></span>
	    </td>
	</tr>
<?
					break;

case 'basket':
?>
	<tr id="param<?=$key ?>" <?=$class ?>>
	<td class="svet_td" width="15%"><?=$value['name'] ?>:</td>
	<td class="svet_td2 selectParamView" colspan="2">

	    <div id="selectParamView<?=$key ?>" class="cursor paramEditCheckbox width25">Показать свойства</div>
    	<div class="paramEdit<?=$key ?>">
        	<div class="paramMiddle checkboxDelete"><select name="delete<?=$key ?>" class="width99">
            		<option value="-1">Не выбрано</option>
            		<? foreach($value['key'] as $key2=>$key3) { ?>
            		<option value="<?=$key2 ?>"><?=$value['value'][$key2] ?></option>
            		<? } ?>
        		</select></div>
    	    <div class="paramEdit paramMiddle"><?=cmfAdminView::onclickType1("if(confirm('Удалить?')) {$jsModul}.postAjax('paramDelChecbox', $key);", '[удалить]') ?></div>
        </div>
	    <div class="paramEdit<?=$key ?>">
    	    <div class="paramMiddle"><input name="newId<?=$key ?>" id="newId<?=$key ?>" type="text"></div>
    	    <div class="paramEdit paramMiddle"><?=cmfAdminView::onclickType1("{$jsModul}.postAjax('paramAdd', $key, 'newId{$key}');", 'Добавить'); ?> </div>
    	</div>
        <script language="JavaScript">
        cmf.admin.param.hideShow('<?=$key ?>');
        </script>

        <span class="selectCheckboxPrice">
	    <? foreach($value['key'] as $key2=>$key3) {
	        if($basket) {
                echo "<label>". $form->html($value['price'][$key2], 'class="width50 paramPrice"')
                    .'&nbsp;'. $form->html($value['dump'][$key2], 'class="width25px cHide"')
                    .'&nbsp;'. $form->html($key3) .'</label>';
	        } else {
    	        echo $form->html($key3);
	        }
	    }?></span>
	    </td>
	</tr>
<?
					break;

				default:
?>
	<tr id="param<?=$key ?>" <?=$class ?>>
	<td class="svet_td"><?=$value['name'] ?>:</td>
	<td class="svet_td3" colspan="2"><?=$form->html($key, 'class="width99" rows="5"') ?></td>
	</tr>
<?

			} ?>
<? } ?>

<? }

}

?>
