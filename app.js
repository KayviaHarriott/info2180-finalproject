"use strict";

document.addEventListener('DOMContentLoaded', function() {
    //alert("Document loaded");
    var form = document.querySelector('form');
    var pageTitle = document.getElementById("title");

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
    let genericAJAXReq = (responseDiv, method, newPgTitle, query = "") => {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onload = function() {
            responseDiv.innerHTML = xmlhttp.responseText;
        }

        var link = `bugTracker.php?a=${newPgTitle}`;
        if (query != "") {
            link = `bugTracker.php?a=${newPgTitle}&${query}`;
        } // End-if

        xmlhttp.open(method, link, true);
        xmlhttp.send();
    }; // End-genericAJAXReq

    //TO-DO (add anything you think we need to do)
    //-- css needed for background to fill on homepage for <aside>/sidebar and be absolute
    //-- inline font images
    //-- need to change inputs to without class in css?
    //-- we need to check the password entered before going to new user, to make sure password has the needed characters, format etc
    //-- users cant click other buttons until they sign in
    //-- need 'new issue button' on home/dashboard



    //-->Home/Dashboard Link
    document.getElementsByClassName("dashboard")[0]
    .addEventListener("click", function(event){
        event.preventDefault();
        var responseDiv = document.getElementById("to-change");
        let newPgTitle = "Issues";
        pageTitle.innerHTML = `<h1>${newPgTitle}</h1>`;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onload = function() {
            //pageTitle.innerHTML = `<h1>${newPgTitle}</h1>`;
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
    });

    //-->Add User Link
    document.getElementsByClassName("add-user")[0]
    .addEventListener("click", function(event){
        event.preventDefault();
        let responseDiv = document.getElementById("to-change");
        let newPgTitle = "New User";
        pageTitle.innerHTML = `<h1>${newPgTitle}</h1>`;
        genericAJAXReq(responseDiv, "GET", newPgTitle);
    });

    //-->New Issue Link
    document.getElementsByClassName("new-issue")[0].
    addEventListener("click", function(event){
        event.preventDefault();
        let responseDiv = document.getElementById("to-change");
        let newPgTitle = "New Issue";
        pageTitle.innerHTML = `<h1>${newPgTitle}</h1>`;
        genericAJAXReq(responseDiv, "GET", newPgTitle);
    });

    //-->Logout Link
    document.getElementsByClassName("log-out")[0]
    .addEventListener("click", function(event){
        event.preventDefault();
        let responseDiv = document.getElementById("to-change");
        let newPgTitle = "Sign In";
        pageTitle.innerHTML = `<h1>${newPgTitle}</h1>`;
        genericAJAXReq(responseDiv, "GET", newPgTitle);
    });
});