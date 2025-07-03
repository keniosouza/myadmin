<?php

$string = 'Hoje será "FODA", e com isso \'Demais\', garrafa D\'água';


echo str_replace(array('"', "'"), "`", $string);