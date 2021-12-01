document.addEventListener('DOMContentLoaded', function() {
    //alert("Document loads");
    var form = document.querySelector('form');
    var textField = document.getElementsByClassName('name-alias-field')[0].value;

    document.getElementById("submit-button").addEventListener("click", function(event){
    event.preventDefault()
    var textField = document.getElementsByClassName('name-alias-field')[0].value;

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                if (xmlhttp.responseText != "Superhero not found"){
                    document.getElementById("result").innerHTML = xmlhttp.responseText;
                }
                
            }
            else{
                document.getElementById("result").innerHTML = xmlhttp.responseText;
            }
    };
    xmlhttp.open("GET", "superheroes.php?a=" + textField, true);
    xmlhttp.send();

    });

});