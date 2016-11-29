<?php
cDebug::sqlOff();


list($parent1, $parent2, $parent3, $_menu, $_subMenu) = cAdmin::menu()->sub()->getId();

end($_menu);
$this->assing('end', key($_menu));
reset($_menu);
$this->assing('menu', $_menu);


$this->assing('subMenu', $_subMenu);
$this->assing('subMenuEnd', count($_subMenu)-1);

$this->assing('parent1', $parent1);
$this->assing('parent2', $parent2);
$this->assing('parent3', $parent3);

cDebug::sqlOn();

$counters = cRegister::sql()->placeholder("SELECT id, counters FROM ?t WHERE visible='yes' ORDER BY pos ", db_seo_counters)
        ->fetchRowAll(0, 1);
$this->assing('counters', implode(' ', $counters));

?>