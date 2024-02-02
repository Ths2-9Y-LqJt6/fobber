<?php
if(is_file('config.php') && is_file('helpers.php')) {
    require_once('helpers.php');
    require_once('config.php');
} else {
    $err = new stdClass();
    $err->status = 'config.php</code> or <code>helpers.php</code> files not found';
    print json_encode($err);
    exit();
}

if($_GET && isset($_GET['membership'])){
    print readMemberStatsCache($membersCacheFile);
} elseif($_GET && isset($_GET['fetch_membership'])){
    $json = retrieveMemberStatsFromRemote($remoteMembershipUrl);
    $result = cacheMemberStats($membersCacheFile, $json);
    print "wrote $result bytes to $membersCacheFile with json $json";
} else {
    $recentFobsObj = getFob($file, $eventCount);
    print json_encode($recentFobsObj);
}