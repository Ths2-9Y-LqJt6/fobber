<?php
require_once ('helpers.php');
$file = 'fobbers.txt';
$recentFobsObj = getFob($file);
print json_encode($recentFobsObj);