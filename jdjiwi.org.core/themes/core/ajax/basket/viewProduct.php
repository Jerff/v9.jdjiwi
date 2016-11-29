<?php



//$r = cRegister::request();


$productId = (int)cInput::post()->get('productId');
if($productId) {
    cRegister::sql()->placeholder("UPDATE ?t SET `view`=`view`+1 WHERE id=?", db_product, $productId);
}



?>