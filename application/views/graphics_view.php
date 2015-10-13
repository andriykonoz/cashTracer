<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Главная</title>
    <link rel="stylesheet" href="libs/bootstrap/bootstrap-grid-3.3.1.min.css"/>
    <link rel="stylesheet" href="libs/bootstrap/bootstrap.css"/>
    <link rel="stylesheet" href="libs/font-awesome-4.2.0/css/font-awesome.min.css"/>
    <link rel="stylesheet" href="libs/fancybox/jquery.fancybox.css"/>
    <link rel="stylesheet" href="libs/owl-carousel/owl.carousel.css"/>
    <link rel="stylesheet" href="libs/countdown/jquery.countdown.css"/>
    <link rel="stylesheet" type="text/css" href="css/sheet.css"/>
    <link rel="stylesheet" type="text/css" href="css/graphics.css"/>
    <script type="text/javascript" src="js/jquery-1.6.2.js"></script>
    <script type="text/javascript" src="js/graphics.js"></script>
    <script src="js/chart.js"></script>


</head>
<body>

<?php include "application/views/nav_menu.php"; ?>

<section>
    <div class="container">
        <div class="row">
            <div class="col-md-12" id="area"></div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h3>Spending statistic</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <canvas id="spendings_chart"></canvas>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <form action="" id="spending_bar">
                    <?php
                    function show_choices($type, $data){
                        $items = $data[$type];
                        foreach ($items as $item) {
                            echo "<label class='radio-inline' id='{$item}'><div class='icon'
                            id='icon_{$item}'></div><input type='checkbox'  value='{$item}' id='chb_{$item}' checked>{$item}</label>";
                        }
                    }
                    show_choices('spendings', $data);
                    ?>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h3>Earning statistic</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <canvas id="earnings_chart"></canvas>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <form action="" id="earning_bar">
                    <?php
                    show_choices('earnings', $data);
                    ?>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h3>Global statistic</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <canvas id="global_chart"></canvas>
            </div>
        </div>


    </div>
</section>

</body>
</html>