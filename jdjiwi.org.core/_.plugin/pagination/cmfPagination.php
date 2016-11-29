<?php


cLoader::library('pagination/cmfPaginationView');
class cmfPagination {

    static public function generate($page, $count, $limit, $pageLimit, $func, $arg=null){
        $max = ceil($count/$limit);
        $res = array();
        if($max>1) {
            $left = floor($page-$pageLimit/2);
            if($left<0) $left = 0;
            $right = $left + $pageLimit+1;
            if($left>0) {
                $res[1] = 1;
                if($left!=1) $res[$left] = '...';
            }
            for($i=$left+1; $i<$right and $i<=$max; $i++) {
                $res[$i]=$i;
            }
            if($right<$max) {
                if($right!=$max) $res[$right] = '...';
                $res[$max] = $max;

            }
        }
        if(!$res) {
            return false;
        }
        $_page = array();
        foreach($res as $key=>$value) {
            $func($_page, $key, $value, $arg);
            $_page[$key]['name'] = $value;
            if($key==$page) $_page[$key]["is"] = true;
        }
        return array('prev'=>isset($_page[$page-1]) ? $_page[$page-1] : false,
                     'next'=>isset($_page[$page+1]) ? $_page[$page+1] : false,
                     'list'=>$_page);
    }

    static public function view($_page){
    	return cmfPaginationView::view($_page);
    }

    static public function viewHistoty($_page){
    	return cmfPaginationView::viewHistoty($_page);
    }

}

?>
