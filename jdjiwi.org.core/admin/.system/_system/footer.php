<?php

cDebug::sqlOff();
$counters = cRegister::sql()->placeholder("SELECT id, counters FROM ?t WHERE visible='yes' ORDER BY pos ", db_seo_counters)
        ->fetchRowAll(0, 1);
$this->assing('counters', implode(' ', $counters));
cDebug::sqlOn();
?>