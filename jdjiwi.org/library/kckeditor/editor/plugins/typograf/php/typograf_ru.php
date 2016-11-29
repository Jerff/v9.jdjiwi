<?php
/**
* Функция возвращает результат post запроса
* $host - хост сайта куда предполагается делать post запросы. Напр., имеется сайт
*    http://www.typograf.ru/webservice/, хостом в данном случае является www.typograf.ru
* $script - имя каталога или скрипта, который обрабатывает ваш post запрос.
*    Для сайта http://www.typograf.ru/webservice/ обработчиком будет каталог /webservice/.
* $data - это данные формата имя=значение, которые передаются для обработки. Для веб-сервиса
*    http://www.typograf.ru/webservice/ необходимо передать значение переменной text, поэтому
*    значение переменной $data будет text=текст для типографирования.
*/

function post($host, $script, $data)
{

	$fp = fsockopen($host, 80, $errno, $errstr, 30);
	if ($fp === false)
		return "Сервер не отвечает";
	fputs($fp, "POST {$script} HTTP/1.1\n");
	fputs($fp, "Host: {$host}\n");
	fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
	fputs($fp, "Content-length: " . strlen($data) . "\n");
	fputs($fp, "User-Agent: PHP Script\n");
	fputs($fp, "Connection: close\n\n");
	fputs($fp, $data);
	while(fgets($fp, 2048) != "\r\n" && !feof($fp));
	unset($buf);
	$buf = '';
	while(!feof($fp))
		$buf .= fread($fp,2048);
	fclose($fp);
	return $buf;
}

$word = $_POST['text'];
$xml = '';

echo post('www.typograf.ru', '/webservice/', 'text='.$word.'&chr=UTF-8&xml='.urlencode($xml));
?>