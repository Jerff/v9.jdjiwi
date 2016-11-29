<?php

function cmfDirFileNew($dir, $file, $is=false) {
	$new = substr($file, 0, 10);
	$dir .= $new .'/';
	if($is and !is_dir($dir)) {
		mkdir($dir, 0777);
	}
	return  $dir . $file;
}


function cmfDirCompileNewPatch($url) {
	$_url = explode('/', $url);

	$new = '';
	$count = count($_url)-1;
	foreach($_url as $k=>$v) {
		if($k) $new .= '/';
		if($count==$k) {
			$new .= $v;
		} else {
			$new .= $v;
            $dir = $new;

			if(!is_dir($dir)) {
            	mkdir($dir, 0744);
            }
		}
	}
	return $new;
}


function cmfReadDir($dir, $pos=0) {
	$_dir = array();
	if(is_dir($dir)) {
		$d = dir($dir);
		while (false !== ($entry = $d->read())) {
		    if(strpos($entry, '.')===0) continue;
		    $entry = $dir . $entry;
		    if(is_dir($entry)) {
		    	$_dir[] = '';
		    	$_dir[] = 'Раздел '. $entry;
		    	$d2 = dir($entry);
				while (false !== ($entry2 = $d2->read())) {
				    if(strpos($entry2, '.')===0) continue;
				    $entry2 = $entry .'/'. $entry2;
				    if(is_file($entry2)) {
				    	$_dir[$entry2] = substr($entry2, $pos);
				    }
				}
				$d2->close();
		    } else {
		    	$_dir[$entry] = substr($entry, $pos);
		    }
		}
		$d->close();
	}
	return $_dir;
}


function cmfReadDir2($dir) {
	$_dir = array();
	if(is_dir($dir)) {
		$d = dir($dir);
		while (false !== ($entry = $d->read())) {
		    if(strpos($entry, '.')===0) continue;
		    $entry = $dir . $entry;
		    if(is_file($entry)) {
		    	$_dir[$entry] = $entry;
		    }
		}
		$d->close();
	}
	return $_dir;
}



?>
