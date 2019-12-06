<?php

// where you want to save the file
$file = 'fobbers.txt';

// valid fields you accept
$requiredVars = array('user', 'handle', 'color');

// optionally include a path to logo
$logo = null;

// number of recent events to show
$eventCount = 6;

// function you want to use to protect against command line injection and XSS
$cleanseFunction = 'cleanse';