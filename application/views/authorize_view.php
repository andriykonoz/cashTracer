<!DOCTYPE html>
<html lang="ru">
<head>
    <base href="/main/" />
    <meta charset="utf-8">
    <title>Authorize</title>
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
                <h2>Enter your login and password</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 form-style-5">
                <form action="/main/authorize/login" method="post">
                    <p>Login:</p>
                    <input type="text" name = 'login'>
                    <p>Password:</p>
                    <input type="password" name = 'password'>
                    <input type="submit" value = 'Log In' name = 'button' >
                    <p>If you forgot password <a href="/main/authorize/recover">click here</a></p>
                    <p>If you want sign in <a href="/main/authorize/reg">click here</a></p>
                </form>

            </div>
        </div>
    </div>
</section>

</body>
</html>