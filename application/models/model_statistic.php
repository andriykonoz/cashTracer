<?php
require("connector.php");
require("tools.php");


class Model_Statistic extends Model
{
    /*
     * Return data for statistic page for specific date
     * Structure of returned object
     * array
     *    |_'earned_money' = (array)item
     *    |                          |_'name' = (string value) name of category
     *    |                          |_'type_amounts' = (array) amounts of money from specific category
     *    |                          |_'sum_amount' = summary amount of money from specific category
     *    |
     *    |_'spended_money' = (array)item
     *    |                          |_'name' = (string value) name of category
     *    |                          |_'type_amounts' = (array) amounts of money from specific category
     *    |                          |_'sum_amount' = summary amount of money from specific category
     *    |
     *    |_'total_earned' = total earned amount of money from all earn categories
     *    |
     *    |_'total_spended' = total spended amount of money from all spend categories
     */
    function get_data()
    {
        session_start();
        // If year or month not numbers, then they replaced with current date
        if (!$this->is_date_correct()) {
            $date_array = getdate();
            $date = $date_array['year'] . '-' . $date_array['mon'] . '-00';
        } else {
            $date = trim($_POST['year']) . '-' . trim($_POST['month']) . '-00';
        }

        return array(
            'earned_money' => $this->fetch_data('earning', $date),
            'spended_money' => $this->fetch_data('spending', $date),
            'total_earned' => $this->get_total_amount_of('earning', $date),
            'total_spended' => $this->get_total_amount_of('spending', $date),
            'date' => substr(str_replace("-", "/", $date), 0, 6)
        );
    }

    private function is_date_correct()
    {
        return (is_numeric(trim($_POST['year'])) && is_numeric(trim($_POST['month'])));
    }

    private function fetch_data($type, $date)
    {
        $mysqli = Connector::get_connection();

        $result = $mysqli->query("SELECT * FROM {$type}_categories");

        $type_categories = array();
        while ($row = $result->fetch_assoc()) {
            $type_categories[] = array($row['id'], $row['name']);
        }

        $type_data = array();
        foreach ($type_categories as $category_item) {
            $earning_item = array();
            $earning_item['name'] = $category_item[1];
            $category_id = intval($category_item[0]);

            $statement = $mysqli->prepare("SELECT (amount) FROM {$type}s WHERE category = ? AND date = '{$date}' AND user_id = {$_SESSION['id_user']}");

            $statement->bind_param('i', $category_id);
            $statement->bind_result($result);
            $statement->execute();

            $type_amounts = array();
            while ($statement->fetch()) {
                $type_amounts[] = format_amount($result);
            }
            $earning_item["{$type}_amounts"] = $type_amounts;

            $result = $mysqli->query("SELECT sum(amount) FROM {$type}s WHERE category = {$category_id}  AND date = '{$date}' AND user_id = {$_SESSION['id_user']}");
            $sum_amount = $result->fetch_assoc();

            $earning_item["sum_amount"] = format_amount($sum_amount['sum(amount)']);
            $type_data[] = $earning_item;
        }
        $mysqli->close();
        return $type_data;
    }
    /*
     * Select summary amount of money for specific date and type
     */
    private function get_total_amount_of($type, $date)
    {
        $mysqli = Connector::get_connection();

        $query_result = $mysqli->query("SELECT sum(amount) FROM {$type}s  WHERE date = '{$date}' AND user_id = {$_SESSION['id_user']}");
        $result = $query_result->fetch_assoc();
        return format_amount($result['sum(amount)']);

        $mysqli->close();

    }
}