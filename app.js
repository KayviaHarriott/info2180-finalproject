"use strict";

document.addEventListener('DOMContentLoaded', function() {
    //alert("Document loaded");
    var form = document.querySelector('form');
    var pageTitle = document.getElementById("title");

    //TO-DO (add anything you think we need to do)
    //-- need event listeners for filters i.e. ALL, OPEN, MY TICKETS
    //-- css needed for background to fill on homepage for <aside>/sidebar and be absolute
    //-- inline font images
    //-- need to change inputs to without class in css?


    //-->Sign In Submission Form
    //-->not complete
    document.getElementsByClassName("dashboard")[0].addEventListener("click", function(event){
        event.preventDefault();
        //alert("Dashboard button listener works");
        var pageTitle = document.getElementById("title");
        //var textField = document.getElementsByClassName('title')[0].value; //placeholder

       /* //sanitise inputs
        let fname = document.querySelector("#f-name").value.replace( /(<([^>]+)>)/ig, "");
        let lname = document.querySelector("#l-name").value.replace( /(<([^>]+)>)/ig, "");
        let pword = document.querySelector("#p-word").value.replace( /(<([^>]+)>)/ig, "");
        let email = document.querySelector("#email").value.replace( /(<([^>]+)>)/ig, "");
*/
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
                if (true ) {
                    if (true ){
                        pageTitle.innerHTML = "<h1>Issues</h1>";
                        document.getElementById("to-change").innerHTML = xmlhttp.responseText;
                    }

                }
                else{
                    document.getElementById("title").innerHTML = " ";
                }
        };
        xmlhttp.open("GET", "bugTracker.php?a=" + pageTitle.innerText, true);  //change to the field to go to php
        xmlhttp.send();
    });

    //-->New Issue Submission Form
    //-->not complete
    document.getElementsByClassName("dashboard")[0].addEventListener("click", function(event){
        event.preventDefault();
        //alert("Dashboard button listener works");
        var pageTitle = document.getElementById("title");
        //var textField = document.getElementsByClassName('title')[0].value; //placeholder

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
                if (true ) {
                    if (true ){
                        pageTitle.innerHTML = "<h1>Issues</h1>";
                        document.getElementById("to-change").innerHTML = xmlhttp.responseText;

                    }

                }
                else{
                    document.getElementById("title").innerHTML = " ";
                }
        };
        xmlhttp.open("GET", "bugTracker.php?a=" + pageTitle.innerText, true); //change to the field to go to php
        xmlhttp.send();
    });


    //-->New User Submission Form
    //-->not complete
    document.getElementsByClassName("dashboard")[0].addEventListener("click", function(event){
        event.preventDefault();
        //alert("Dashboard button listener works");
        var pageTitle = document.getElementById("title");
        //var textField = document.getElementsByClassName('title')[0].value; //placeholder

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
                if (true ) {
                    if (true ){
                        pageTitle.innerHTML = "<h1>Issues</h1>";
                        document.getElementById("to-change").innerHTML = xmlhttp.responseText;

                    }

                }
                else{
                    document.getElementById("title").innerHTML = " ";
                }
        };
        xmlhttp.open("GET", "bugTracker.php?a=" + pageTitle.innerText, true); //change to the field to go to php
        xmlhttp.send();
    });



    //-->Home/Dashboard Link
    document.getElementsByClassName("dashboard")[0].addEventListener("click", function(event){
        event.preventDefault();
        //alert("Dashboard button listener works");
        var pageTitle = document.getElementById("title");
        //var textField = document.getElementsByClassName('title')[0].value; //placeholder

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
                if (true ) {
                    if (true ){
                        pageTitle.innerHTML = "<h1>Issues</h1>";
                        document.getElementById("to-change").innerHTML = xmlhttp.responseText;

                    }

                }
                else{
                    document.getElementById("title").innerHTML = " ";
                }
        };
        xmlhttp.open("GET", "bugTracker.php?a=" + pageTitle.innerText, true); //change to the field to go to php
        xmlhttp.send();


    });

    //-->Add User Link
    document.getElementsByClassName("add-user")[0].addEventListener("click", function(event){
        event.preventDefault();
        //alert("Add user button listener works");
        var pageTitle = document.getElementById("title");
        //var textField = document.getElementsByClassName('title')[0].value; //placeholder

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
                if (true ) {
                    if (true ){
                        pageTitle.innerHTML = "<h1>New User</h1>";
                        document.getElementById("to-change").innerHTML = xmlhttp.responseText;

                    }

                }
                else{
                    document.getElementById("title").innerHTML = " ";
                }
        };
        xmlhttp.open("GET", "bugTracker.php?a=" + pageTitle.innerText, true); //change to the field to go to php
        xmlhttp.send();


    });

    //-->New Issue Link
    document.getElementsByClassName("new-issue")[0].addEventListener("click", function(event){
        event.preventDefault();
        //alert("New issue button listener works");
        var pageTitle = document.getElementById("title");
        //var textField = document.getElementsByClassName('title')[0].value; //placeholder

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
                if (true ) {
                    if (true ){
                        pageTitle.innerHTML = "<h1>New Issue</h1>";
                        document.getElementById("to-change").innerHTML = xmlhttp.responseText;

                    }

                }
                else{
                    document.getElementById("title").innerHTML = " ";
                }
        };
        xmlhttp.open("GET", "bugTracker.php?a=" + pageTitle.innerText, true); //change to the field to go to php
        xmlhttp.send();

    });

    //-->Logout Link
    document.getElementsByClassName("log-out")[0].addEventListener("click", function(event){
        event.preventDefault();
        //alert("Logout button listener works");
        var pageTitle = document.getElementById("title");
        //var textField = document.getElementsByClassName('title')[0].value; //placeholder

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
                if (true ) {
                    if (true ){
                        pageTitle.innerHTML = "<h1>Sign In</h1>";
                        document.getElementById("to-change").innerHTML = xmlhttp.responseText;

                    }

                }
                else{
                    document.getElementById("title").innerHTML = " ";
                }
        };
        xmlhttp.open("GET", "bugTracker.php?a=" + pageTitle.innerText, true); //change to the field to go to php
        xmlhttp.send();
    });


});