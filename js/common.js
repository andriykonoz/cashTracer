$(document).ready(function (){
        var balance = parseInt($(".balance h2 span").text());

        document.getElementById("balance").style.color = "red";

        if(balance > 0){
            document.getElementById("balance").style.color = "green";
        } else{
            document.getElementById("balance").style.color = "red";
        }
    }

);