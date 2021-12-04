"use strict";

//TO-DO (add anything you think we need to do)
//-- css needed for background to fill on homepage for <aside>/sidebar and be absolute
//-- inline font images
//-- need to change inputs to without class in css?
//-- we need to check the password entered before going to new user, to make sure password has the needed characters, format etc
//-- users cant click other buttons until they sign in
//-- need 'new issue button' on home/dashboard

/**
 * @brief summary
 *
 * long description
 *
 * @param Type <var> Description
 * @return type
 * @throws conditon
 */
function isValidEmail(email){
    let alpha = "A-Za-z";
    let alphaNum = `${alpha}0-9`;
    let tld = `\\.[${alpha}][${alphaNum}]*`;
    let hostname = `[${alpha}][${alphaNum}\\-]*[${alphaNum}]*`;
    let username = `[${alpha}][${alphaNum}\\-\\.]*[${alphaNum}]*`;
    let emailRegExp = new RegExp(`^${username}@${hostname}${tld}$`);
    let e = document.getElementById("email").value.trim().match(emailRegExp);
    return (e != null);
} // End-isValidEmail

/**
 * @brief Executes an AJAX GET request
 *
 * @param string url The link to send the request to
 * @param function callback The code to be run once the response is received
 */
function ajaxGetReq(url, callback) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onload = function() {callback(this)};
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}; // End-ajaxGetReq

/**
 * @brief Sends a POST request with the data provided to the link specified
 *        and returns the result
 *
 * @param string url The location to POST the data to
 * @param JSON data The data to be sent in the body of the POST request
 * @param string type The Content-Type of the POST request
 * @return any
 */
async function ajaxPostReq(url, data, type = ""){
    let contentType;
    switch (type) {
        case "json":
            contentType = "application/json";
            break;
        default:
            contentType = "application/x-www-form-urlencoded";
            break;
    } // End-switch-case

    // request options
    let req = {
        "method": "POST",
        "body": JSON.stringify(data),
        "headers": {
            "Content-Type": contentType
        }
    };

    return await fetch(url, req).then(res => res);
} // End ajaxPostReq

//----------------------------------------------------------------------------
// Global Variables
//----------------------------------------------------------------------------

let responseDiv;
let pageTitle;

//----------------------------------------------------------------------------
// Button Listeners
//----------------------------------------------------------------------------

document.addEventListener('DOMContentLoaded', function(event) {
    responseDiv = document.getElementById("to-change");
    pageTitle = document.getElementById("title");
    login();
}); // End-documentOnLoad

function login() {
    let signIn = document.getElementById("sign-in-form");
    signIn.addEventListener("submit", verifyUser);

    let btns = document.getElementsByTagName("aside")[0]
        .getElementsByTagName("ul")[0].getElementsByTagName("li");
    var b;
    for (b of btns) {
        b.addEventListener("click", function (event) {
            event.preventDefault();
            let newPgTitle = "Sign In";
            pageTitle.innerHTML = `<h1>${newPgTitle}</h1>`;

            let link = `bugTracker.php?a=${newPgTitle}`;
            ajaxGetReq(link, function(xmlhttp) {
                responseDiv.innerHTML = xmlhttp.responseText;
                signIn = document.getElementById("sign-in-form");
                signIn.addEventListener("submit", verifyUser);
            });
        });
    } // End-for
} // End-login

function verifyUser(event){
    event.preventDefault();
    let email = document.getElementById("email").value;
    let passwd = document.getElementById("p-word").value;

    if (isValidEmail(email)) {
        let data = {
            "a": "sign-in",
            "email": email,
            "passwd": passwd
        };
        res = ajaxPostReq(`${window.location.origin}/bugTracker.php`,
            data, "json");
    } // End-if

    bugTracker(); // REMEMBER TO PUT THIS BACK INSIDE THE IF SO IT'S ACTUALLY
    // RESTRICTED TO VALID LOGINS
} // End-verifyUser

function bugTracker() {
    // Load in the Dashboard
    let newPgTitle = "Issues";
    pageTitle.innerHTML = `<h1>${newPgTitle}</h1>`;
    let link = `bugTracker.php?a=${newPgTitle}`;
    ajaxGetReq(link, function(xmlhttp) {
        responseDiv.innerHTML = xmlhttp.responseText
    });

    //-->Home/Dashboard Link
    document.getElementsByClassName("dashboard")[0]
    .addEventListener("click", dashboardListener);

    //-->Add User Link
    document.getElementsByClassName("add-user")[0]
    .addEventListener("click", createUserListener);

    //-->New Issue Link
    document.getElementsByClassName("new-issue")[0]
    .addEventListener("click", createIssueListener);

    //-->Logout Link
    document.getElementsByClassName("log-out")[0]
    .addEventListener("click", logoutListener);
} // End-bugTracker

function dashboardListener(event){
    event.preventDefault();
    let newPgTitle = "Issues";
    pageTitle.innerHTML = `<h1>${newPgTitle}</h1>`;

    let link = `bugTracker.php?a=${newPgTitle}`;
    ajaxGetReq(link, function(xmlhttp) {
        responseDiv.innerHTML = xmlhttp.responseText;

        const filter = document.getElementById("filter");
        var btn;
        for (btn of filter.getElementsByTagName("a")){
            // Adds an event listener that loops through the buttons and
            // removes the selected-filter class from all of them then
            // adds the selected filter class to itself
            btn.addEventListener("click", function(event){
                event.preventDefault();

                const filter = document.getElementById("filter");
                for (btn of filter.getElementsByTagName("a")){
                    btn.classList.remove("selected-filter");
                } // End-for
                event.target.classList.add("selected-filter");

                let issueLst = document.getElementById("issue-list");
                let query = `filter=${event.target.innerText}`;
                let link = `bugTracker.php?a=Issue List&${query}`;
                ajaxGetReq(link, function(xmlhttp) {
                    issueLst.innerHTML = xmlhttp.responseText
                });
            }); // End-eventListener

            // Initialises the All button as the selected one
            if (btn.id == "all-button") {
                btn.classList.add("selected-filter");
            } // End-if
        } // End-for
    });
} // End-dashboardListener

function createUserListener(event) {
    event.preventDefault();
    let newPgTitle = "New User";
    pageTitle.innerHTML = `<h1>${newPgTitle}</h1>`;
    let link = `bugTracker.php?a=${newPgTitle}`;
    ajaxGetReq(link, function(xmlhttp) {
        alert("Create User");
    });
} // End-createUserListener

function createIssueListener(event) {
    event.preventDefault();
    let newPgTitle = "New Issue";
    pageTitle.innerHTML = `<h1>${newPgTitle}</h1>`;
    let link = `bugTracker.php?a=${newPgTitle}`;
    ajaxGetReq(link, function(xmlhttp) {
        alert("Create Issue");
    });
} // End-createIssueListener

function logoutListener(event) {
    event.preventDefault();
    let newPgTitle = "Sign In";
    pageTitle.innerHTML = `<h1>${newPgTitle}</h1>`;
    let link = `bugTracker.php?a=${newPgTitle}`;
    ajaxGetReq(link, function(xmlhttp) {
        alert("Logout");
        // I imagine there would be extra code here to close the PHP session

        // Remove all the action listeners for the other buttons before the
        // login() call below so they don't trigger anymore when you log out
        login();
    });
} // End-logoutListener
