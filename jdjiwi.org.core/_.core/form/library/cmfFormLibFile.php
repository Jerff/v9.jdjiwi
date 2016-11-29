<?php


// ------------- работа с файлами -------------
// копирование файлов на сервер
function cmfCopyFile($folder_in, $name, $folder_out=null) {
	if(is_file($folder_in.$name)) {
		if(is_null($folder_out)) {
			$folder_out = $folder_in;
			$filename = rand(0, 9999).$name;
		} else $filename = $name;
		while(file_exists($folder_out.$filename)) $filename = rand(0, 9999) . $name;
		if(copy($folder_in.$name, $folder_out.$filename)) return $filename;
		else return null;
	}
	return null;
}

function cmfUploadUrl($folder_in, $name, $folder_out=null) {
	if(is_null($folder_out)) {
		$folder_out = $folder_in;
		$filename = rand(0, 9999).$name;
	} else $filename = $name;
	while(file_exists($folder_out.$filename)) $filename = rand(0, 9999) . $name;
	if(copy($folder_in.$name, $folder_out.$filename)) return $filename;
	else return null;
}

// загрузка файлов на сервер
function cmfUploadFile($folder, $upload) {
	$name = $file = cConvert::translate($upload['name']);
	while(file_exists($folder . $name)) $name = rand(0, 9999) . $file;
	if(move_uploaded_file($upload['tmp_name'], $folder . $name)) {
		chmod($folder . $name, cDir::mode);
		return $name;
	}
	return null;
}

// удаление файла на сервере
function cmfFileUnlick($file) {
	if(is_file($file)) unlink($file);
}


?>
