<?php

// where you want to save the file
$file = 'fobbers.txt';

// where to cache json
$membersCache = 'members.json';

// remote membership location of https://github.com/synshop/ShopIdentifyer/
$remoteMembershipUrl = 'https://membership.example.com';

// valid fields you accept
$requiredVars = array('user', 'handle', 'color');

// optionally include a path to logo
$logo = null;

// number of recent events to show
$eventCount = 6;

// function you want to use to protect against command line injection and XSS
$cleanseFunction = 'cleanse';