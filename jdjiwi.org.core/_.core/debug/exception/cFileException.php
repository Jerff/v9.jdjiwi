<?php

class cFileException extends cException {

    protected $name = 'Работа с файловой системой';

    const noWritable = 'файл не доступен для записи';
    const noWritableFile = 'Невозможна запись в файл';
    const noWritableDir = 'Невозможно создать файл в папке';
    const no = 'отсуствует';
    const no3 = 'отсуствует';
    const no4 = 'отсуствует';

}

?>