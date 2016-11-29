<?php




cLoader::library('contact/cmfContact');
//$r = cRegister::request();
$regionId =  cInput::post()->get('regionId');
$countryId =  cInput::post()->get('value');

if(!$regionId) return;
if($js = cmfCache::getParam('changeCountry', $countryId)) {

} else {

    $select = new cmfFormSelectInt();
    $select->addElement( 0, 'Отсуствует');
    if($countryId) {
        foreach(cmfContact::region($countryId) as $k=>$v) {
            $select->addElement($k, $v);
        }
    }
    $js = $select->jsUpdateSelect($regionId);
    cmfCache::setParam('changeCountry', $countryId, $js, 'country,region');
}
cAjax::get()->script($js)
              ->script("$('.selectRegion>.formElement>.selectCountry').resetSS();");


?>