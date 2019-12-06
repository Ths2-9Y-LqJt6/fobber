<?php

require_once ('helpers.php');

$file = 'fobbers.txt';

$count = 1;
$recentFobsObj = getFob($file);
print json_encode($recentFobsObj);