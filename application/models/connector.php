<?php
/*
 * This class localize information about database.
 */
class Connector
{
    static private $connection;


    static function get_connection()
    {
        $host = "localhost";
        $user = "root";
        $password = "root";
        $base = "cash_statistic";

   //     if (empty(self::$connection)) {
            self::$connection = new mysqli($host, $user, $password, $base);
            if (self::$connection->connect_errno) {
                echo "Failed to connect to MySQL: (" . self::$connection->connect_errno . ") " . self::$connection->connect_error;
            }
    //    }
        return self::$connection;

    }
}