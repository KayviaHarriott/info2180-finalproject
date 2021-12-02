document.addEventListener('DOMContentLoaded', function() {
    //alert("Document loads");
    var form = document.querySelector('form');
    //var textField = document.getElementsByClassName('name-alias-field')[0].value;

    //first screen, new user to dashboard
    document.getElementById("submit-button").addEventListener("click", function(event){
    event.preventDefault()
    //alert("Button works");
   
    var textField = document.getElementsByClassName('title')[0].value;
    var pageTitle = document.getElementById("title");

    var reloadarea = document.getElementById('result'); //content to reload

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
            if (true /*xmlhttp.readyState == 4 && xmlhttp.status == 200*/) {
                if (true /*xmlhttp.responseText != "Superhero not found"*/){
                    //document.getElementById("result").innerHTML = xmlhttp.responseText;
                    pageTitle.innerHTML = "<h1>Issues</h1>";
                    reloadarea.innerHTML = "here";

                    // pageTitle.innerHTML("Issues");
                   //$(".title").text("Issues");

                }
                
            }
            else{
                //document.getElementById("result").innerHTML = xmlhttp.responseText;
                document.getElementById("title").innerHTML = "<h1>WORK2</h1>";
                
            }
    };
    xmlhttp.open("GET", "bugTracker.php?a=" + textField, true);
    xmlhttp.send();

        
    });

    //for New Issue Page
    document.getElementsByClassName("new-issue")[0].addEventListener("click", function(event){
        event.preventDefault()
       
        var textField = document.getElementsByClassName('title')[0].value;    
        var pageTitle = document.getElementById("title");
        //? pageTitle.innerHTML = "<h1>New Issue</h1>";
    
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
                if (true /*xmlhttp.readyState == 4 && xmlhttp.status == 200*/) {
                    if (true /*xmlhttp.responseText != "Superhero not found"*/){
                        pageTitle.innerHTML = "<h1>New Issue</h1>";
    
                    }                    
                }
                else{
                    document.getElementById("title").innerHTML = "<h1>WORK2</h1>";
                }
        };
        xmlhttp.open("GET", "bugTracker.php?a=" + textField, true);
        xmlhttp.send(); 
        });

});