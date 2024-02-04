<?php

if(is_file('config.php') && is_file('helpers.php')) {
    require_once('helpers.php');
    require_once('config.php');
    $cacheDefeat = md5(md5_file('config.php') .  md5_file('fobber.css') . md5_file('fobber.js'));
} else {
    echo '<h1>Error</h1><code>config.php</code> or <code>helpers.php</code> files not found :(';
    exit();
}
if(is_file('newCleanse.php')) {
    require_once('newCleanse.php');
}

if ($logo !== null){
    $logoHtml = '<img class="inner" id="thelogo" src="' . $logo . '?cacheBust="'. $cacheDefeat.'">';
} else {
    $logoHtml = '';
}

// handle POSTs of events
if (isset($_POST) && sizeof($_POST)>0){
    $savedObj = saveFob($_POST, $requiredVars, $cleanseFunction, $file);
    print $savedObj->status;
    exit();
}

?>
<html>
    <head>
        <title>Recent Fobs</title>
    </head>

    <body>

        <div id="members">
            <h1>Members</h1>
            <div id="content2">
                <div class="inner" id="membersInner"></div>
            </div>
        </div>

        <div id="fobs">
            <h1>Recent Fobs</h1>
            <div id="content">
                <div class="inner" id="recentFobs"></div>
            </div>
        </div>
        <?php echo $logoHtml ?>

        <script type="text/javascript" src="fobber.js?cacheBust="<?= $cacheDefeat ?>"></script>
        <link rel="stylesheet"  href="fobber.css?cacheBust="<?= $cacheDefeat ?>" />

    </body>
</html>