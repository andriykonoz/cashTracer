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
    <script type="text/javascript" src="js/jquery-1.6.2.js"></script>
    <script type="text/javascript" src="js/common.js"></script>


</head>
<body>

<?php include "application/views/nav_menu.php"; ?>

<?php
require("parts/date_comboboxes.php");
?>

<section class="input_forms">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <!-- <form action="/main/update" id="spending_form" method="post"> -->
                <form action="" id="spending_form" method="post">

                    <h3>Spended</h3>

                    <p>Category:</p>
                    <select name="category" id="spending_category" class="form-control ctg_form">
                        <?php
                        $categories = $data[0];
                        foreach ($categories as $category) {
                            echo "<option value=\" " . $category . " \">" . ucfirst($category) . "</option>\n";
                        }

                        ?>
                    </select>

                    <p>Year/Month</p>

                    <select name="year" class="date form-control">
                        <?php
                        Parts::get_year_combobox();
                        ?>
                    </select>
                    <select name="month" class="date form-control">
                        <?php
                        Parts::get_month_combobox();
                        ?>
                    </select>

                    <p>Cost:</p>
                    <input type="text" name="amount" id="spending" class="form-control">
                    <input type="hidden" name="category_type" value="spending">
                    <input type="button" value="submit" class="button btn btn-primary"
                           onclick="sendCostInAjax('spending')">
                    <!--    <input type="submit" value="submit" class="button"> -->

                </form>
            </div>

            <div class="col-md-3">

                <form action="" id="earning_form" method="post">

                    <h3>Earned</h3>

                    <p>Category:</p>
                    <select name="category" id="earning_category" class="form-control ctg_form">
                        <?php
                        $categories = $data[1];
                        foreach ($categories as $category) {
                            echo "<option value=\" " . $category . " \">" . ucfirst($category) . "</option>\n";
                        }

                        ?>
                    </select>

                    <p>Year/Month/Day</p>

                    <select name="year" class="date form-control">
                        <?php
                        Parts::get_year_combobox();
                        ?>
                    </select>
                    <select name="month" class="date form-control">
                        <?php
                        Parts::get_month_combobox();
                        ?>
                    </select>

                    <p>Cost:</p>
                    <input type="text" name="amount" id="earning" class="form-control">
                    <input type="hidden" name="category_type" value="earning">
                    <input type="button" value="submit" class="button btn btn-primary"
                           onclick="sendCostInAjax('earning')">

                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3" id="spending_view">
                <h3>Recently added spended money:</h3>
                <table class="table table-striped costs">
                    <thead>
                    <th>Category</th>
                    <th>Amount</th>
                    </thead>
                    <tbody id="spending_list"></tbody>
                </table>
            </div>

            <div class="col-md-3" id="earning_view">
                <h3>Recently added earned money:</h3>
                <table class="table table-striped costs">
                    <thead>
                    <th>Category</th>
                    <th>Amount</th>
                    </thead>
                    <tbody id="earning_list"></tbody>
                </table>
            </div>
        </div>

    </div>


</section>


<script>

    function sendCostInAjax($form_type) {

        var cost = document.getElementById($form_type).value;

        if (!isNaN(parseFloat(cost)) && isFinite(cost)) {
            document.getElementById($form_type).style.border = '1px grey solid';
            document.getElementById($form_type + '_view').style.display = 'block';

            jQuery.ajax({
                url: "/main/update",
                type: "POST",
                data: jQuery("#" + $form_type + "_form").serialize(),
                error: function () {
                    document.writeln("Error occured during sending information to server");
                }
            });

            $("#" + $form_type + "_list").append('<tr><td>' + document.getElementById($form_type + '_category').value + '</td><td> '
                + document.getElementById($form_type).value + '</td></tr>');
            document.getElementById($form_type).value = '';
        } else {
            document.getElementById($form_type).style.border = '1px red solid';
        }

    }
</script>

</body>
</html>