<?php

cLoader::library('user/cUserEnter');

switch (cInput::get()->get('name')) {
    case 'UserEnter':
    case 'leftUserEnter':
    case 'basket':
        $userEnter = new cUserEnter();
        $userEnter->run();
        break;
}
?>