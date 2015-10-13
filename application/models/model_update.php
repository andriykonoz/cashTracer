<?php
require("connector.php");

class Model_Update extends Model
{
    /*
     * Function add information about money to database.
     * Before adding, function check where input data is correct.
     * If data not valid error message will be printed without adding
     * data into table.
     * For preventing SQL-injection used prepared statements.
     */
    public function set_data()
    {
        $mysqli = Connector::get_connection();
        session_start();

        $category = trim($_POST["category"]);
        $cost = $_POST["amount"];
        $category_type = $_POST["category_type"];
        // Checking where year and month is numbers
        if (is_numeric(trim($_POST['year'])) && is_numeric(trim($_POST['month']))) {

            $date = trim($_POST['year']) . '-' . trim($_POST['month']) . '-00';
            // Checking of category
            if ($this->type_valid($category_type)) {
                $statement = $mysqli->prepare("SELECT id FROM {$category_type}_categories WHERE name = ?");
                $statement->bind_param("s", $category);
                $statement->execute();
                $statement->bind_result($category_id);
                $statement->fetch();
                // If received category cant be finded in database then null will be returned.
                // Here returned value checked for null.
                if (!empty($category_id)) {
                    if (is_numeric($cost)) {
                        $cost = floatval($cost);
                        $category_id = intval($category_id);

                        $mysqli = Connector::get_connection();
                        $statement = $mysqli->prepare("INSERT INTO {$category_type}s (category, amount, date, user_id) VALUES (?,?,?,?)");
                        $statement->bind_param("idsi", $category_id, $cost, $date, $_SESSION['id_user']);
                        $statement->execute();
                    } else {
                        echo "Error occured! 'Cost' value must be number, cost = " . $cost . "<br>";
                    }
                } else {
                    echo "Error occured! Cant find category '" . $category . "'<br>";
                }
            } else {
                echo "Error occured! Incorrect date: '" . $date . "'<br>";
            }
        }


        $mysqli->close();
    }

    private function type_valid($type)
    {
        return ($type === "earning" || $type === "spending");
    }

}