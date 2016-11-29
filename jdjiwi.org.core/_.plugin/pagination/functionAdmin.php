<?php


function &cmfAdminPagination($pg, $count, $limit, $link){
	$max = ceil($count/$limit);
	$return = array();
	if($max>1) {
		$left = floor($pg-$link/2)-1;
		if($left<0) $left = 0;
		$right = $left + $link + ($left ? 2 : 1);
		if($left>0) $return[$left] = '<<';
		for($i=$left+1; $i<$right and $i<=$max; $i++)
			$return[$i]=$i;
		if(ceil($pg/$link)<ceil($max/$link)) $return[$right] = '>>';
	}
	return $return;
}

?>
