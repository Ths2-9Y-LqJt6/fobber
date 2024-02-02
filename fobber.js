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
            let mainContainer = document.getElementById("recentFobs");
            mainContainer.innerHTML = '';
            let div = document.createElement("div");
            div.innerHTML = 'Error: ' + err;
            mainContainer.prepend(div);
            console.log('error: ' + err);
        });

}/**
 * Do AJAX call and then make callback to displayFobEvents() with results
 */
function getRecentMembers() {

    // huge thanks to https://howtocreateapps.com/fetch-and-display-json-html-javascript/ !!
    fetch('ajax.php?membership=1')
        .then(function (response) {
            return response.json();
        })
        .then(function (data) {
            displayMembers(data);
        })
        .catch(function (err) {
            // Ahh!  Error! Print it to the page so admins know
            let mainContainer = document.getElementById("membersInner");
            mainContainer.innerHTML = '';
            let div = document.createElement("div");
            div.innerHTML = 'Error: ' + err;
            mainContainer.prepend(div);
            console.log('error: ' + err);
        });

}

function cacheMembershipJson(){
    fetch('ajax.php?fetch_membership=1').then(r => console.log("membership cached"));
}

/**
 * given a previous time, return a relative human time from now
 * thanks to https://stackoverflow.com/a/6109105
 * @param int previous in epoch seconds - NOT epoch ms!!
 * @returns {string}
 * {"total_door_access":161,"total_free":3,
 * "total_membership":158,"total_need_onboarding":0,"total_not_vetted":36,"total_paused":64,"total_vetted":58}
 */

function displayMembers(data){
    // erase prior fobs
    let mainContainer = document.getElementById("membersInner");
    mainContainer.innerHTML = '';

    // update DOM with new fob events
    let div;
    if (data.total_door_access) {
        const calc_vetted = data.total_vetted + data.total_free;
        const calc_total = calc_vetted + data.total_not_vetted;

        div = document.createElement("div");
        div.innerHTML = "Vetted: " + calc_vetted + "";
        mainContainer.prepend(div);

        div = document.createElement("div");
        div.innerHTML = "Regular: " + data.total_not_vetted + "";
        mainContainer.prepend(div);

        div = document.createElement("div");
        div.innerHTML = "Total: " + calc_total + "";
        mainContainer.prepend(div);

    } else {
        let div = document.createElement("div");
        div.innerHTML = 'Error: ' + JSON.stringify(data);
        mainContainer.prepend(div);
    }
}

/**
 * given a previous time, return a relative human time from now
 * thanks to https://stackoverflow.com/a/6109105
 * @param int previous in epoch seconds - NOT epoch ms!!
 * @returns {string}
 */
function displayFobEvents(data){
    // erase prior fobs
    let mainContainer = document.getElementById("recentFobs");
    mainContainer.innerHTML = '';

    // update DOM with new fob events
    if (data.status === 'good') {
        let objectCount = Object.keys(data.results).length;
        for (let i = 0; i < objectCount; i++) {

            // create a new div, populate with handle and time, add it to the DOM
            let div = document.createElement("div");
            div.innerHTML = data.results[i].handle + " " + timeDifference(data.results[i].time) + "";
            mainContainer.prepend(div);

            // highlight first item with a textShadow
            if(i === (objectCount -1)){
                // get local color let for legibility
                let color = data.results[i].color;

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
        let div = document.createElement("div");
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

    let msPerMinute = 60 * 1000;
    let msPerHour = msPerMinute * 60;
    let msPerDay = msPerHour * 24;
    let msPerMonth = msPerDay * 30;
    let msPerYear = msPerDay * 365;

    let elapsed = current - previous;

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
        return ' ' + Math.round(elapsed/msPerDay) + ' days ago';
    }
    else if (elapsed < msPerYear) {
        return ' ' + Math.round(elapsed/msPerMonth) + ' months ago';
    }
    else {
        return 'hella years ago';
    }
}

// keep refresh count
let refreshCount = 1;

// init page with initial call to getRecentFobs(), then call every 1sec with setInterval()
cacheMembershipJson();
getRecentFobs();
window.setInterval(function(){
    getRecentFobs();
    getRecentMembers();
}, 1000);
window.setInterval(function(){
    cacheMembershipJson();
}, 1000000);