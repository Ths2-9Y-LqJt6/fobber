<?php

if(is_file('config.php') && is_file('helpers.php')) {
    require_once('helpers.php');
    require_once('config.php');
} else {
    echo '<h1>Error</h1><code>config.php</code> or <code>helpers.php</code> files not found :(';
    exit();
}

if ($logo !== null){
    $logoHtml = '<img class="inner" id="thelogo" src="' . $logo . '">';
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
        <?php echo $logoHtml ?>
        <h1>Recent Fobs</h1>
        <div id="content">
            <div class="inner" id="recentFobs"></div>
        </div>

        <script type="text/javascript" src="fobber.js"></script>
        <link rel="stylesheet"  href="fobber.css" />

    </body>
</html>