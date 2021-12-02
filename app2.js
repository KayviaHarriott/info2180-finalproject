"use strict";

//LOAD THE DOCUMENT
window.addEventListener("load", function() {    
    let btn = document.querySelector("#submit-button");

    btn.addEventListener("click", function(e) {
        e.preventDefault();
        
        let fname = document.querySelector("#f-name").value.replace( /(<([^>]+)>)/ig, "");
        let lname = document.querySelector("#l-name").value.replace( /(<([^>]+)>)/ig, "");
        let pword = document.querySelector("#p-word").value.replace( /(<([^>]+)>)/ig, "");
        let email = document.querySelector("#email").value.replace( /(<([^>]+)>)/ig, "");

        //post to the db


        //get from the db
        fetch("world.php?country=" + txt) //chnage name of php file
            .then(response => {
                if (response.ok) {
                  return response.text();
                } else {
                  return Promise.reject("Error!");
                }
            })
            .then(data => {
                let result = document.querySelector("#result");
                result.innerHTML = data;
            })
            .catch(error => console.log(error))

    });

});