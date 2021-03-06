"use strict";

// #TODO (add anything you think we need to do)
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
 * @param function callback The code to run after receiving the request
 * @param string type The Content-Type of the POST request
 * @return any
 */
function ajaxPostReq(url, data, type = "", callback){
    let contentType;
    switch (type) {
        case "json":
            contentType = "application/json";
            break;
        default:
            contentType = "application/x-www-form-urlencoded";
            break;
    } // End-switch-case

    console.log(contentType);
    // request options
    let req = {
        "method": "POST",
        "body": JSON.stringify(data),
        "headers": {
            "Content-Type": contentType
        }
    };

    fetch(url, req).then(function(res) {callback(res)});
} // End-ajaxPostReq

//----------------------------------------------------------------------------
// Global Variables
//----------------------------------------------------------------------------

const baseUrl = `${window.location.origin}/bugTracker.php`;
let responseDiv;
let pageTitle;
let asideBtns;
let asideBtnListners;

//----------------------------------------------------------------------------
// Button Listeners
//----------------------------------------------------------------------------

document.addEventListener('DOMContentLoaded', function(event) {
    responseDiv = document.getElementById("to-change");
    pageTitle = document.getElementById("title");

    asideBtns = {
        "dashboard": document.getElementsByClassName("dashboard")[0],
        "add-user": document.getElementsByClassName("add-user")[0],
        "new-issue": document.getElementsByClassName("new-issue")[0],
        "log-out": document.getElementsByClassName("log-out")[0]
    };
    asideBtnListners = {
        "dashboard": dashboardListener,
        "add-user": createUserListener,
        "new-issue": createIssueListener,
        "log-out": logoutListener
    };

    login();
}); // End-documentOnLoad

function login() {
    loadSignIn();
} // End-login

function loadSignIn(){
    let newPgTitle = "Sign In";
    pageTitle.innerHTML = `<h1>${newPgTitle}</h1>`;
    let link = `bugTracker.php?a=${newPgTitle}`;
    ajaxGetReq(link, function(xmlhttp) {
        responseDiv.innerHTML = xmlhttp.responseText;

        // Remove all the event listeners for the other buttons so they don't
        // trigger anymore when you log out
        var b;
        for (b in asideBtns) {
            asideBtns[b].removeEventListener("click", asideBtnListners[b]);
        } // End-for

        let signIn = document.getElementById("sign-in-form");
        signIn.addEventListener("submit", verifyUser);
    });
} // End-loadSignIn

function verifyUser(event){
    event.preventDefault();
    let email = document.getElementById("email").value;
    let passwd = document.getElementById("p-word").value;

    if (isValidEmail(email)) {
        let link = `${baseUrl}?z=sign-in&email=${email}&passwd=${passwd}`;
        ajaxGetReq(link, function(xmlhttp){
            let res = JSON.parse(xmlhttp.responseText);
            console.log(res);
            if (res.auth) {
                bugTracker();
            }else{
                console.log(res);
                alert("Invalid credentials");
            } // End-if
        });
    }else{
        alert("Invalid email");
    } // End-if
} // End-verifyUser

function bugTracker() {
    // Load in the Dashboard
    loadDashboard();

    // Add the event listeners for the buttons so
    var b;
    for (b in asideBtns) {
        asideBtns[b].addEventListener("click", asideBtnListners[b]);
    } // End-for
} // End-bugTracker

function dashboardListener(event){
    event.preventDefault();
    loadDashboard();
} // End-dashboardListener

function loadDashboard(){
    let newPgTitle = "Issues";
    pageTitle.innerHTML = `<h1>${newPgTitle}</h1>`;

    let link = `bugTracker.php?a=${newPgTitle}`;
    ajaxGetReq(link, function(xmlhttp) {
        responseDiv.innerHTML = xmlhttp.responseText;
        loadIssueList("ALL");

        const filter = document.getElementById("filter");
        var btn;
        for (btn of filter.getElementsByTagName("a")){
            // Adds an event listener that loops through the buttons and
            // removes the selected-filter class from all of them then
            // adds the selected filter class to itself
            btn.addEventListener("click", function(event){
                event.preventDefault();

                for (btn of filter.getElementsByTagName("a")){
                    btn.classList.remove("selected-filter");
                } // End-for
                event.target.classList.add("selected-filter");

                loadIssueList(event.target.innerText);
            }); // End-eventListener

            // Initialises the All button as the selected one
            if (btn.id == "all-button") {
                btn.classList.add("selected-filter");
            } // End-if
        } // End-for
    });
} // End-loadDashboard

function loadIssueList(filter) {
    let issueLst = document.getElementById("issue-list");
    let link = `bugTracker.php?a=Issue List&filter=${filter}`;
    ajaxGetReq(link, function(xmlhttp) {
        issueLst.innerHTML = xmlhttp.responseText;

        let issues = issueLst.getElementsByTagName("tbody")[0]
            .getElementsByTagName("tr");
        var i;
        for (i of issues){
            i.getElementsByTagName("td")[0]
            .addEventListener("click", function(event) {
                event.preventDefault();
                let iid = event.target.getElementsByTagName("span")[0]
                    .innerText;
                iid = parseInt(iid.substring(1, iid.length));
                loadIssueDetails(iid);
            });
        } // End-for
    });
} // End-loadIssueList

function loadIssueDetails(iid){
    let link = `bugTracker.php?a=Issue Detail&iid=${iid}`;
    ajaxGetReq(link, function(xmlhttp) {
        responseDiv.innerHTML = xmlhttp.responseText;

        document.getElementById("mark-closed-button")
        .addEventListener("click", function(event) {
            event.preventDefault();
            let link = `${baseUrl}?z=update-issue-status&id=${iid}&status=closed`;
            ajaxGetReq(link, function(xmlhttp) {
                let temp = xmlhttp.responseText;
                let res = JSON.parse(temp);
                if (res.status == "success") {
                    loadIssueDetails(iid);
                }else{
                    alert(res.status);
                } // End-if
            });
        });

        document.getElementById("mark-in-progress-button")
        .addEventListener("click", function(event) {
            event.preventDefault();
            let link = `${baseUrl}?z=update-issue-status&id=${iid}&status=in-progress`;
            ajaxGetReq(link, function(xmlhttp) {
                let temp = xmlhttp.responseText;
                let res = JSON.parse(temp);
                if (res.status == "success") {
                    loadIssueDetails(iid);
                }else{
                    alert(res.status);
                } // End-if
            });
        });
    });
} // End-loadIssueDetails

function createUserListener(event) {
    event.preventDefault();
    let newPgTitle = "New User";
    pageTitle.innerHTML = `<h1>${newPgTitle}</h1>`;
    let link = `bugTracker.php?a=${newPgTitle}`;

    ajaxGetReq(link, function(xmlhttp) {
        responseDiv.innerHTML = xmlhttp.responseText;

        document.getElementById("new-user-button")
        .addEventListener("click", function(event) {
            event.preventDefault();
            let fields = {
                "f-name": document.getElementById("f-name"),
                "l-name": document.getElementById("l-name"),
                "email": document.getElementById("email"),
                "passwd": document.getElementById("p-word")
            };

            let link = `${baseUrl}?z=add-user`;
            var f;
            for (f in fields) {
                link = `${link}&${f}=${fields[f].value}`;
            } // End-for
            ajaxGetReq(link, function(xmlhttp) {
                let temp = xmlhttp.responseText;
                let res = JSON.parse(temp);
                if (res.status != "success") {
                    if (res.status != null) {
                        alert(res.status);
                    }else{
                        var a;
                        var err = "Invalid: ";
                        for (a in res) {
                            err = `${err}${a}\n`;
                        } // End-for
                        alert(err);
                    } // End-if
                }else{
                    alert("User Created");
                } // End-if
            });
        })
    });
} // End-createUserListener

function createIssueListener(event) {
    event.preventDefault();
    let newPgTitle = "New Issue";
    pageTitle.innerHTML = `<h1>${newPgTitle}</h1>`;
    let link = `bugTracker.php?a=${newPgTitle}`;
    ajaxGetReq(link, function(xmlhttp) {
        responseDiv.innerHTML = xmlhttp.responseText;

        document.getElementById("new-issue-button")
        .addEventListener("click", function(event) {
            event.preventDefault();

            let fields = {
                "title": document.getElementById("i-title"),
                "desc": document.getElementById("i-desc"),
                "assign": document.getElementById("i-assign"),
                "type": document.getElementById("i-type"),
                "pri": document.getElementById("i-priority")
            };

            let link = `${baseUrl}?z=add-issue`;
            var f;
            console.log(fields);
            for (f in fields) {
                if (fields[f].value.length > 0) {
                    link = `${link}&${f}=${fields[f].value}`;
                } // End-if
            } // End-for
            ajaxGetReq(link, function(xmlhttp) {
                let temp = xmlhttp.responseText;
                let res = JSON.parse(temp);
                var err = "Invalid: ";
                console.log(res);
                if (res.status != "success") {
                    alert(res.status);
                }else{
                    alert("Issue Created");
                } // End-if
            });
        });
    });
} // End-createIssueListener

function logoutListener(event) {
    event.preventDefault();
    loadSignIn();
} // End-logoutListener
