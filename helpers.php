<?php
/**
 * helper functions for fobber
 */

/**
 * take input, cleanse so it's safe to write to disk, return cleansed result
 * @param string $input
 * @return string cleansed!!
 */
function cleanse($input){
    $result = trim(preg_replace("/[^A-Za-z0-9\.,# ]/", '', $input));
    return $result;
}

/**
 * given an array from POST, make sure it's valid, cleansed and save it in json by appending to file
 * @param array $input array, very likely POST
 * @param array $validVars array of valid keys in input
 * @param string $cleanseFunction callback to cleanse with
 * @return stdClass
 */
function saveFob($input, $validVars, $cleanseFunction, $location = null){
    $toSaveObj = new  stdClass();
    $toSaveObj->results = array();

    // check for good file to save in
    if (!is_file($location) || !is_writable($location)) {
        $toSaveObj->status = 'no file available/writable at "' . $location . '"';
    } else {

        // validate and cleanse
        foreach ($input as $key => $value) {
            if (in_array($key, $validVars)) {
                $toSaveObj->results[$key] = $cleanseFunction($value);
            }
        }

        // check we have enough valid vars to store
        if (sizeof($toSaveObj->results) < sizeof($validVars)) {
            $toSaveObj->status = 'not enough vars in input. need all these: ' . implode(', ' , $validVars);
        } else {
            $toSaveObj->results['time'] = time();
            $result = file_put_contents($location, json_encode($toSaveObj->results) . "\n", FILE_APPEND);

            if ($result === false) {
                $toSaveObj->status = 'witting to "' . $location . '" failed';
            } else {
                $toSaveObj->status = 'good';
            }
        }
    }

    // return what we tog
    return $toSaveObj;
}

/**
 * get the last $num of folks who fobbed in from file $location in reverse chronological order
 * @param
 * @return stdClass of arrays of json stored in file
 */
function getFob($location = null, $fobberCount = 10){
    $resultObj = new  stdClass();
    $resultObj->results = array();

    if ($location != null) {
        $location = cleanse($location);
        if ($location != '' && is_file($location) && is_readable($location)){
            $linesTxt = shell_exec("tail -n $fobberCount $location");
            $linesRawAry = explode("\n", $linesTxt);

            foreach ($linesRawAry as $lineTxt) {
                $tmpResult = json_decode($lineTxt, true);
                if (is_array($tmpResult) && sizeof($tmpResult)>0) {
                    $resultObj->results[] = $tmpResult;
                }
            }
            krsort($resultObj->results );

            $resultObj->status = 'good';
        } else {
            $resultObj->status = $location . ' is empty or not a file/readable';
        }
    } else {
        $resultObj->status = $location . ' is null';
    }
    return $resultObj;
}

function cacheMemberStats($location, $json){
    // check for good file to save in
//    if (!is_file($location) || !is_writable($location)) {
    if ( !is_writable(dirname($location))) {
        error_log("ERROR can't save json for membership!");
        return false;
    } else {
        error_log(" saving json for membership!");
        return file_put_contents($location, $json);
    }
}

function readMemberStatsCache($location){
    if (is_file($location)){
        return file_get_contents($location);
    } else {
        return json_encode(array('total_vetted'=>"no cache file"));
    }
}

function retrieveMemberStatsFromRemote($url){
    return file_get_contents($url);
}