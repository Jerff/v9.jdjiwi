<?php


//$r = cRegister::request();

cLoader::library('form/cmfFeedback');
$feedback = new cmfFeedback(cInput::get()->get('productId'));
$feedback->run();


?>