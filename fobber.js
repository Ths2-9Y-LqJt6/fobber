/**
 * Do AJAX call and then make callback to displayFobEvents() with results
 */
function getRecentFobs() {

    // huge thanks to https://howtocreateapps.com/fetch-and-display-json-html-javascript/ !!
    fetch('ajax.php')
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            displayFobEvents(data);
        })
        .catch(function (err) {
            // Ahh!  Error! Print it to the page so admins know
            var mainContainer = document.getElementById("recentFobs");
            mainContainer.innerHTML = '';
            var div = document.createElement("div");
            div.innerHTML = 'Error: ' + err;
            mainContainer.prepend(div);
            console.log('error: ' + err);
        });

}

/**
 * given a previous time, return a relative human time from now
 * thanks to https://stackoverflow.com/a/6109105
 * @param int previous in epoch seconds - NOT epoch ms!!
 * @returns {string}
 */
function displayFobEvents(data){
    // erase prior fobs
    var mainContainer = document.getElementById("recentFobs");
    mainContainer.innerHTML = '';

    // update DOM with new fob events
    if (data.status === 'good') {
        var objectCount = Object.keys(data.results).length;
        for (var i = 0; i < objectCount; i++) {

            // create a new div, populate with handle and time, add it to the DOM
            var div = document.createElement("div");
            div.innerHTML = data.results[i].handle + " " + timeDifference(data.results[i].time) + "";
            mainContainer.prepend(div);

            // highlight first item with a textShadow
            if(i === (objectCount -1)){
                // get local color var for legibility
                var color = data.results[i].color;

                //  if they have two colors, alternate between them by splitting the sting by comma
                if (color.includes(',')){

                    // if refresh count is odd, take first color, else, second
                    if (parseInt(refreshCount) % 2 === 1){
                        color = color.split(",")[0];
                    } else {
                        color = color.split(",")[1];
                    }
                }

                // set drop shadow CSS
                div.style.textShadow = '0em 0em 0.2em ' + color + ',   0em 0em 0.2em ' + color + ',  0em 0em 0.2em ' + color;
            }
        }
        refreshCount++;
    } else {
        var div = document.createElement("div");
        div.innerHTML = 'Error: ' + data.status;
        mainContainer.prepend(div);
    }
}

/**
 * given a previous time, return a relative human time from now
 * thanks to https://stackoverflow.com/a/6109105
 * @param int previous in epoch seconds - NOT epoch ms!!
 * @returns {string}
 */
function timeDifference(previous) {

    current = new Date().getTime();

    // previous is passed as epoch to the sec, not ms.  make it ms like getTime() result
    previous = previous*1000;

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

// keep refresh count
var refreshCount = 1;

// init page with initial call to getRecentFobs(), then call every 1sec with setInterval()
getRecentFobs();
window.setInterval(function(){
    getRecentFobs();
}, 1000);