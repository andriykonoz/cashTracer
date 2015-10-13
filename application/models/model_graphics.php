<?php
require("connector.php");

class Model_Graphics extends Model
{
    /*
     * return data for bulding checkboxes.
     * Structure of array
     * array
     *      |_'earnings'=[array of earning category names]
     *      |_'spendings'=[array of spending category names]
     */
    function get_data()
    {
        return array(
            "earnings" => $this->fetch_type_categories("earning"),
            "spendings" => $this->fetch_type_categories("spending")
        );
    }
    /*
     * return array with category names for specific type
     */
    private function fetch_type_categories($type)
    {
        $mysqli = Connector::get_connection();

        $result = $mysqli->query("SELECT name FROM {$type}_categories");

        $categories = array();
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row['name'];
        }

        return $categories;
    }
    /*
     * return JSON object that contains information for building charts.
     * Structure of JSON object:
     * object
     *      |_'earnings'
     *      |        |_(array)item
     *      |                    |_'name'= earnings category name
     *      |                    |_'amount'
     *      |                            |_(array)item = [date, amount of money]
     *      |_'spendings'
     *               |_(array)item
     *                           |_'name'= spendings category name
     *                           |_'amount'
     *                                   |_(array)item = [date, amount of money]
     */
    function get_graphics_json_data()
    {
        $mysqli = Connector::get_connection();

        return json_encode(array(
            'earnings' => $this->fetch_data('earning', $mysqli),
            'spendings' => $this->fetch_data('spending', $mysqli),
            'global' => $this->fetch_global_data($mysqli)
        ));
    }

    /*
     * Fetch data from database for specific type with specific connection.
     * Return data array which have structure:
     * (array)item
     *         |_'name'= type category name
     *         |_'amount'
     *                 |_(array)item = [date, amount of money]
     */
    private function fetch_data($type, $connection)
    {
        $type_categories_descr = $this->get_type_categories("$type", $connection);

        $type_info = array();
        foreach ($type_categories_descr as $category_descr_item) {
            $type_category = array();
            $type_category['name'] = $category_descr_item[1];

            $category = intval($category_descr_item[0]);

            $result = $connection->query("SELECT date, sum(amount) FROM {$type}s WHERE category ={$category}
            AND user_id = {$_SESSION['id_user']} GROUP BY DATE");

            $type_amount = array();
            while($row = $result->fetch_assoc()){
                $type_amount[] = array(substr($row['date'],0,7), $row['sum(amount)']);
            }
            $type_category['amount'] = $type_amount;
            $type_info[] = $type_category;
        }
        return $type_info;
    }
    /*
     * Select from database all categories for specific type.
     * Return array with array as item witch have two rows - id and name
     */
    private function get_type_categories($type, $connection)
    {
        $result = $connection->query("SELECT * FROM {$type}_categories");

        $categories = array();
        while ($row = $result->fetch_assoc()) {
            $categories[] = array($row['id'], $row['name']);
        }
        return $categories;
    }

    /*
     * Function fetch global statistic about earnings and spendings.
     * Return array with next structure:
     * (array)item
     *          |_'global_earnings' = (array)item = [date, amount of money]
     *          |_'global_spendings' = (array)item = [date, amount of money]
     */
    private function fetch_global_data($connection){
        return array(
            'global_earnings' => $this->get_global_type_amounts('earning', $connection),
            'global_spendings' => $this->get_global_type_amounts('spending', $connection)
        );
    }

    /*
     * Return array with global statistic for earning and spending moneys
     */
    private function get_global_type_amounts($type, $connection){

        $result = $connection->query("SELECT date, sum(amount) FROM  `{$type}s` WHERE user_id = {$_SESSION['id_user']} GROUP BY date");
        $type_amounts = array();
        while($row = $result->fetch_assoc()){
            $type_amounts[] = array($row['date'], $row['sum(amount)']);
        }
        return $type_amounts;

    }


}