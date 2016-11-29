<?

$r = cAjax::get();

$menuStart = $this->run('/admin/menu/start/');
$menuSub = $this->run('/admin/menu/sub/');
$menuEnd1 = $this->run('/admin/menu/end1/');
$menuEnd2 = $this->run('/admin/menu/end2/');

$r->html('#mainIndex', $menuStart . $menuSub . $content . $menuEnd1 . $menuEnd2);


?>