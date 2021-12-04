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
 * @brief Executes the AJAX request
 *
 * @param Element responseDiv The HTML element to render the response in
 * @param String method The type of HTTP request to make
 * @param String newPgTitle The title of the new screen to be loaded
 * @param String query The data to send in the request
 * @return type
 * @throws conditon
 */
function genericAJAXReq(responseDiv, method, newPgTitle, query = "", callback) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onload = function() {
        responseDiv.innerHTML = xmlhttp.responseText;
        if (callback != null){
            callback();
        } // End-if
    }

    var link = `bugTracker.php?a=${newPgTitle}`;
    if (query != "") {
        link = `bugTracker.php?a=${newPgTitle}&${query}`;
    } // End-if

    xmlhttp.open(method, link, true);
    xmlhttp.send();
}; // End-genericAJAXReq

function login() {
    let signIn = document.getElementById("sign-in-form");
    signIn.addEventListener("submit", verifyUser);

    let btns = document.getElementsByTagName("aside")[0]
        .getElementsByTagName("ul")[0].getElementsByTagName("li");
    var b;
    for (b of btns) {
        b.addEventListener("click", function (event) {
            event.preventDefault();
            let responseDiv = document.getElementById("to-change");
            let newPgTitle = "Sign In";
            let pageTitle = document.getElementById("title");
            pageTitle.innerHTML = `<h1>${newPgTitle}</h1>`;
            genericAJAXReq(responseDiv, "GET", newPgTitle, "", () =>{
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
        // post body data
        const data = {
            a: "sign-in",
            email: email,
            passwd: passwd
        };

        // request options
        const req = {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {
                'Content-Type': 'application/json'
            }
        };

        console.log(email);
        fetch(`${window.location.origin}/bugTracker.php`, req)
        .then(res => res.json())
        //.then(res => res.text())
        .then(res => alert(res));
    } // End-if

    bugTracker(); // REMEMBER TO PUT THIS BACK INSIDE THE IF SO IT'S ACTUALLY
    // RESTRICTED TO VALID LOGINS
} // End-verifyUser

function bugTracker() {
    // Load in the Dashboard
    let responseDiv = document.getElementById("to-change");
    let newPgTitle = "Issues";
    let pageTitle = document.getElementById("title");
    pageTitle.innerHTML = `<h1>${newPgTitle}</h1>`;
    genericAJAXReq(responseDiv, "GET", newPgTitle);

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
    let responseDiv = document.getElementById("to-change");
    let newPgTitle = "Issues";
    let pageTitle = document.getElementById("title");
    pageTitle.innerHTML = `<h1>${newPgTitle}</h1>`;

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onload = function() {
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

                let responseDiv = document.getElementById("issue-list");
                let query = `filter=${event.target.innerText}`;
                genericAJAXReq(responseDiv, "GET", "Issue List", query);
            }); // End-eventListener

            // Initialises the All button as the selected one
            if (btn.id == "all-button") {
                btn.classList.add("selected-filter");
            } // End-if
        } // End-for
    }; // End-onload

    let link = `bugTracker.php?a=${newPgTitle}`;
    xmlhttp.open("GET", link, true);
    xmlhttp.send();
} // End-dashboardListener

function createUserListener(event) {
    event.preventDefault();
    let responseDiv = document.getElementById("to-change");
    let newPgTitle = "New User";
    let pageTitle = document.getElementById("title");
    pageTitle.innerHTML = `<h1>${newPgTitle}</h1>`;
    genericAJAXReq(responseDiv, "GET", newPgTitle, "", () => {
        alert("Create User");
    });
} // End-createUserListener

function createIssueListener(event) {
    event.preventDefault();
    let responseDiv = document.getElementById("to-change");
    let newPgTitle = "New Issue";
    let pageTitle = document.getElementById("title");
    pageTitle.innerHTML = `<h1>${newPgTitle}</h1>`;
    genericAJAXReq(responseDiv, "GET", newPgTitle, "", () => {
        alert("Create Issue");
    });
} // End-createIssueListener

function logoutListener(event) {
    event.preventDefault();
    let responseDiv = document.getElementById("to-change");
    let newPgTitle = "Sign In";
    let pageTitle = document.getElementById("title");
    pageTitle.innerHTML = `<h1>${newPgTitle}</h1>`;
    genericAJAXReq(responseDiv, "GET", newPgTitle, "", () => {
        alert("Logout");
        // I imagine there would be extra code here to close the PHP session

        // Remove all the action listeners for the other buttons before the
        // login() call below so they don't trigger anymore when you log out
        login();
    });
} // End-logoutListener

document.addEventListener('DOMContentLoaded', login); // End-documentOnLoad
