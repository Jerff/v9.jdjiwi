<?php

cLoader::library('patterns/cPatternsConfig');

class cFormConfig extends cPatternsConfig {

    protected function init() {
        return array(
            'type' => array(
                'text' => 'cFormText',
                'number' => 'cFormNumber',
                'int' => 'cFormInt',
                'float' => 'cFormFloat',
                'range' => 'cFormRange',
                'password' => 'cFormPassword',
                'email' => 'cFormEmail',
                'textarea' => '',
            ),
            'reform' => array(
                'isInt' => 'cFormReform::int',
                'isFloat' => 'cFormReform::float',
                'isNumber' => 'cFormReform::number',
                'isEmail' => 'cFormReform::specialchars',
//                'isNumber' => 'cFormReform::number',
                'isSpecialchars' => 'cFormReform::specialchars',
            ),
            'filter' => array(
                'isEmpty' => 'cFormFilter::isEmpty',
                'isNoEmpty' => 'cFormFilter::isNoEmpty',
                'isEmail' => 'cFormFilter::isEmail',
                'isUrl' => 'cFormFilter::isUrl',
                'isPassword' => 'cFormFilter::isPassword',
                'numberRange' => 'cFormFilter::numberRange',
                'numberMin' => 'cFormFilter::numberMin',
                'numberMax' => 'cFormFilter::numberMax',
                'strRange' => 'cFormFilter::strRange',
                'strMin' => 'cFormFilter::strMin',
                'strMax' => 'cFormFilter::strMax',
                'confirmName' => 'cFormFilter::confirmName',
            ),
            'input' => array(
                'max'=>'255'
            ),
            'error' => array(
                'requiredFields' => 'Заполните обязательные поля!',
                'security' => 'Неверный ключ сессии',
                'submit' => 'Форма не отправлена!',
                'form' => 'В форме обнаружены ошибки',
                'filter' => array(
                    'isNotEmpty' => 'поле не должно быть пустым',
                    'isEmail' => 'в полe не почтовый адресс',
                    'isNumber' => 'поле должно быть числом',
                    'strRange' => 'поле не должно быть меньше (%min%) и больше (%max%) символов',
                    'strMin' => 'поле не должно быть меньше (%min%) символов',
                    'strMax' => 'поле не должно быть больше (%max%) символов',
                    'numberRange' => 'поле не должно быть меньше (%min%) и больше (%max%)',
                    'numberMin' => 'поле не должно быть меньше (%min%)',
                    'numberMax' => 'поле не должно быть больше (%max%)',
                    'confirmName' => 'Неправильное подтверждение пароля',
                    '435' => '',
                    '435' => '',
                    '435' => '',
                ),
            ),
            'textareaMax' => '65535',
            'textareaMax' => '65535',
            'textareaMax' => '65535',
            'textareaMax' => '65535',
            'textareaMax' => '65535',
            'textareaMax' => '65535',
            'textareaMax' => '65535'
        );
    }

}

?>