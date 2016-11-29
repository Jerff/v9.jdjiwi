<?php

cLoader::library('wysiwyng/cmfWysiwyngDriver');
set_include_path(get_include_path() . PATH_SEPARATOR . cWWWPath .'library/kckeditor/');
include_once(cWWWPath .'library/kckeditor/fckeditor.php');

class cmfWysiwyngKCKeditor extends cmfWysiwyngDriver {

    static protected function getJsPath() {
        return cBaseAppUrl .'library/kckeditor/';
    }

	static public function html($path, $number, $id, $value, $height=null) {
		$oFCKeditor = new FCKeditor($id) ;
		$oFCKeditor->BasePath	= self::getJsPath();
		$oFCKeditor->Value		= $value ;
		if($height) $oFCKeditor->Height = $height;
		$oFCKeditor->ConfigURl		= array('path'=>$path, 'number'=>$number) ;
		return $oFCKeditor->Create() ;
	}

	static public function jsUpdate($id, $value) {
        $value = cJScript::quote($value);
        $js =<<<HTML
FCKeditorAPI.Instances.{$id}.SetHTML('$value');
HTML;
		return $js;
	}

    static public function typograf($id) {
?>
        <br>
        <div title="ТипограF" style="padding: 5px;">
            <a id="typograf<?=$id ?>" href="<?=self::getJsPath() ?>editor/plugins/typograf/typograf2.html?id=<?=$id ?>">
                <img src="<?=self::getJsPath() ?>editor/plugins/typograf/typograf.gif" class="TB_Button_Image">
            </a>
            <script type="text/javascript">
                $("#typograf<?=$id ?>").fancybox({type:'iframe'});
            </script>
        </div>
<?
    }

}

?>