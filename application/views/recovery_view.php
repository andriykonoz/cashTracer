<!DOCTYPE html>
<html lang="ru">
<head>
    <base href="/main/" />
    <meta charset="utf-8">
    <title>Главная</title>
    <link rel="stylesheet" href="libs/bootstrap/bootstrap-grid-3.3.1.min.css"/>
    <link rel="stylesheet" href="libs/bootstrap/bootstrap.css"/>
    <link rel="stylesheet" href="libs/font-awesome-4.2.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="libs/fancybox/jquery.fancybox.css"/>
    <link rel="stylesheet" href="libs/owl-carousel/owl.carousel.css"/>
    <link rel="stylesheet" href="libs/countdown/jquery.countdown.css"/>
    <link rel="stylesheet" type="text/css" href="css/sheet.css"/>
    <link rel="stylesheet" type="text/css" href="css/authorize.css"/>
    <script type="text/javascript" src="js/jquery-1.6.2.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript" src="js/security.js"></script>


</head>
<body>

<section>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-12 form-style-5">
                    <form action="" id = 'recovery'>
                        <p>Login:</p>
                        <input type="text" name="login" id="login" value="test">
                        <p>Email:</p>
                        <input type="email" name="email" id="email" value="mailofoverlord@gmail.com">
                        <input type="button" value="Recover password" onclick="recover()">
                        <h3 id="errors"></h3>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    function recover(){
        var form_elements = document.getElementById('recovery').elements;
        var filled = true;
        for(var i = 0; i < form_elements.length; i++){
            if(form_elements[i].value === ''){
                form_elements[i].style.border = 'red 1px solid';
                document.getElementById('errors').innerHTML = 'All fields must be filled.'
                filled = false;
            } else {
                form_elements[i].style.border = 'none';
            }
        }

        if(!filled){
            return;
        }



        var email_regex = /^[a-z0-9]+@[a-z]+\.[a-z]+$/;
        var email = document.getElementById("email").value;

        if(email_regex.test(email)){

        } else {
            document.getElementById("errors").innerHTML = "Enter correct email";
            return;
        }

        var data = jQuery("#recovery").serialize();
        httpGetAsync("/main/authorize/recoverpassword", log, data);


    }



    function log(data){
        var info = JSON.parse(data);

        //   alert(data);

        alert(data);
        if(info['errors']){
            document.getElementById('errors').innerHTML = info['errors'];
        } else {
            document.getElementById('errors').innerHTML = "Check your email for new password";
        }
    }




    function httpGetAsync(theUrl, callback, data) {
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.onreadystatechange = function () {
            if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
                callback(xmlHttp.responseText);
        };
        //   alert(data);
        xmlHttp.open("POST", theUrl, true); // true for asynchronous
        xmlHttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xmlHttp.send(data);
    }
</script>



</body>
</html>