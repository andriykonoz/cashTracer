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




<?php
include "application/views/nav_menu.php";
require("parts/date_comboboxes.php");

?>

<section>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h4>Information selected for <?php echo $data['date'] ?></h4>
            </div>
            <div class="col-md-4">
                <form action="/main/statistic" class="statistic_date" method="post">
                    <p>Year/Month/Day:</p>

                    <select name="year" class="form-control">
                        <?php
                        Parts::get_year_combobox();
                        ?>
                    </select>
                    <select name="month" class="form-control">
                        <?php
                        Parts::get_month_combobox();
                        ?>
                    </select>

                    <input type="submit" value="submit" class="btn btn-primary">
                </form>
            </div>
        </div>
        <div class="row global_info">
            <div class="col-md-4">
                <div class="total_spended">
                    <h2>Total spended:<span class="spended_total"> -<?php echo $data['total_spended'] ?></span></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="total_earned">
                    <h2>Total earned:<span class="earned_total"> +<?php echo $data['total_earned'] ?></span></h2>
                </div>
            </div>
            <div class="col-md-4">
                <div class="balance">
                    <h2>Balance:
                        <span id="balance">
                            <?php
                            echo $data['total_earned'] - $data['total_spended'];
                            ?>
                        </span>

                    </h2>
                </div>
            </div>
        </div>
        <div class="col-md-6 spended">
            <div class="row">
                <div class="col-md-12">
                    <h2>Spended Money</h2>
                </div>
            </div>

            <div class="row">
                <?php
                $spending_data = $data['spended_money'];

                foreach ($spending_data as $spended_item) {
                    if (!empty($spended_item['spending_amounts'])) {
                        echo "<div class=\"col-md-4 test\">";
                        echo "<h3>" . $spended_item['name'] . "</h3>";
                        echo "<table class=\"table table-striped\">
                    <thead>
                    <th>Amount</th>
                    </thead>
                    <tbody >";

                        $amounts = $spended_item['spending_amounts'];
                        foreach ($amounts as $amount) {
                            echo "<tr><td>- " . $amount . "<td><tr>";
                        }
                        echo "</tbody></table>";
                        echo "<h4> Total amount: <span class='spended_total'>-" . $spended_item['sum_amount'] . "</span></h4>";
                        echo "</div>";
                    }
                }
                ?>
            </div>
        </div>

        <div class="col-md-6 earned">
            <div class="row">
                <div class="col-md-12">
                    <h2>Earned Money</h2>
                </div>
            </div>

            <div class="row">
                <?php
                $earning_data = $data['earned_money'];

                foreach ($earning_data as $earned_item) {
                    if (!empty($earned_item['earning_amounts'])) {
                        echo "<div class=\"col-md-4 test\">";
                        echo "<h3>" . $earned_item['name'] . "</h3>";
                        echo "<table class=\"table table-striped\">
                    <thead>
                    <th>Amount</th>
                    </thead>
                    <tbody >";

                        $amounts = $earned_item['earning_amounts'];
                        foreach ($amounts as $amount) {
                            echo "<tr><td>+ " . $amount . "<td><tr>";
                        }
                        echo "</tbody></table>";
                        echo "<h4> Total amount: <span class='earned_total'>+" . $earned_item['sum_amount'] . "</span></h4>";
                        echo "</div>";
                    }
                }
                ?>
            </div>
        </div>
    </div>
</section>







</body>
</html>