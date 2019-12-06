<?php

/**
 * super simple record POSTs,  show last 10 users who fobbed in from POSTs, AJAX refresh every 1 second
 */

require_once ('helpers.php');

$file = 'fobbers.txt';
$requiredVars = array('user', 'handle', 'color');
$varCount = sizeof($requiredVars);

if (isset($_POST) && sizeof($_POST)>0){
    $savedObj = saveFob($_POST, $requiredVars, 'cleanse', $file);
    print $savedObj->status;
    exit();
}
?>

<div id="myData"></div>

<script>
    function getRecentFobs() {

        // huge thanks to https://howtocreateapps.com/fetch-and-display-json-html-javascript/ !!
        fetch('ajax.php')
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                appendData(data);
            })
            .catch(function (err) {
                console.log('error: ' + err);
            });

        function appendData(data) {
            var mainContainer = document.getElementById("myData");
            mainContainer.innerHTML = '';
            var showme = '';
            if(data.status === 'good') {
                for (var i = 9; i < Object.keys(data.results).length; i--) {
                    var div = document.createElement("div");
                    div.innerHTML = data.results[i].handle + " " + timeDifference(data.results[i].time) + "";
                    mainContainer.appendChild(div);
                }
            } else {
                var div = document.createElement("div");
                div.innerHTML = 'Error: ' + data.status;
                mainContainer.appendChild(div);
            }
        }
    }

    // https://stackoverflow.com/a/6109105
    function timeDifference(previous) {

        current = new Date().getTime();
        previous = previous*1000;
        console.log('current: ' + current + ' prev: ' + previous);
        var msPerMinute = 60 * 1000;
        var msPerHour = msPerMinute * 60;
        var msPerDay = msPerHour * 24;
        var msPerMonth = msPerDay * 30;
        var msPerYear = msPerDay * 365;

        var elapsed = current - previous;

        if (elapsed < msPerMinute) {
            return Math.round(elapsed/1000) + ' seconds ago';
        }

        else if (elapsed < msPerHour) {
            return Math.round(elapsed/msPerMinute) + ' minutes ago';
        }

        else if (elapsed < msPerDay ) {
            return Math.round(elapsed/msPerHour ) + ' hours ago';
        }

        else if (elapsed < msPerMonth) {
            return 'approximately ' + Math.round(elapsed/msPerDay) + ' days ago';
        }

        else if (elapsed < msPerYear) {
            return 'approximately ' + Math.round(elapsed/msPerMonth) + ' months ago';
        }

        else {
            return 'hella years ago';
        }
    }

    getRecentFobs();
    window.setInterval(function(){
        getRecentFobs();
    }, 1000);
</script>