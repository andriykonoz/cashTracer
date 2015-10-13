<?php

require("connector.php");

class Model_Main extends Model
{

    /*
     * Select information about categories for main page.
     * Return array which contains two rows:
     *       1) array with spending categories
     *       2) array with earning categories
     */
    public function get_data()
    {

        $mysqli = Connector::get_connection();
        $res = $mysqli->query("SELECT * FROM spending_categories");

        $spend_categories = array();
        while($row = $res->fetch_assoc()){
            $spend_categories[] = $row["name"];
        }

        $res = $mysqli->query("SELECT * FROM earning_categories");
        $profit_categories = array();
        while($row = $res->fetch_assoc()){
            $profit_categories[] = $row["name"];
        }

        return array(
            $spend_categories,
            $profit_categories
        );


    }
}