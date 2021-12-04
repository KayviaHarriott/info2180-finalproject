"use strict";

document.addEventListener('DOMContentLoaded', function() {
    //alert("Document loaded");
    var form = document.querySelector('form');
    var pageTitle = document.getElementById("title");
    var responseDiv = document.getElementById("to-change");

    /**
     * @brief Executes the AJAX request
     *
     * @param String method The type of HTTP request to make
     * @param String newPgTitle The title of the new screen to be loaded
     * @param String query The data to send in the request
     * @return type
     * @throws conditon
     */
    let genericAJAXReq = (method, newPgTitle, query = "") => {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            pageTitle.innerHTML = `<h1>${newPgTitle}</h1>`;
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
            genericAJAXReq("GET", "Issues");

            //-->Change Filters
            //var filterBtn = document.getElementById("filter-button");
            let allbtn = document.getElementById("all-button");
            let openbtn = document.getElementById("open-button");
            let myticketsbtn = document.getElementById("my-tickets-button");

            $('#all-button, #open-button, #my-tickets-button')
                .on('click', function(event){
                    event.preventDefault();
                    var filterQuery = "";

                    if (this.innerHTML == "ALL"){
                        //alert("All button works");
                        filterQuery = "ALL";
                        //alert(filterQuery)

                        allbtn.classList.add("selected-filter");
                        openbtn.classList.remove("selected-filter");
                        myticketsbtn.classList.remove("selected-filter");
                    }
                    if (this.innerHTML == "OPEN"){
                        //alert("Open button works");
                        filterQuery = "OPEN";
                        //alert(filterQuery)

                        allbtn.classList.remove("selected-filter");
                        openbtn.classList.add("selected-filter");
                        myticketsbtn.classList.remove("selected-filter");
                    }
                    if (this.innerHTML == "MY TICKETS"){
                        //alert("My tickets button works");
                        filterQuery = "MY-TICKETS";
                        //alert(filterQuery)

                        allbtn.classList.remove("selected-filter");
                        openbtn.classList.remove("selected-filter");
                        myticketsbtn.classList.add("selected-filter");
                    }

                genericAJAXReq("GET", "Issues", `filter=${filterQuery}`);
                });
    });

    //-->Add User Link
    document.getElementsByClassName("add-user")[0]
        .addEventListener("click", function(event){
            event.preventDefault();
            genericAJAXReq("GET", "New User");
    });

    //-->New Issue Link
    document.getElementsByClassName("new-issue")[0].
        addEventListener("click", function(event){
            event.preventDefault();
            genericAJAXReq("GET", "New Issue");
    });

    //-->Logout Link
    document.getElementsByClassName("log-out")[0]
        .addEventListener("click", function(event){
            event.preventDefault();
            genericAJAXReq("GET", "Sign In");
    });
});