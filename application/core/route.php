<?php


class Route
{
    static function start()
    {

        $controller_name = "Main";
        $action_name = "index";
        $routes = explode("/", $_SERVER["REQUEST_URI"]);

        if (!empty($routes[2])) {
            $controller_name = $routes[2];
        }

        if (!empty($routes[3])) {
            $action_name = $routes[3];
        }

        if (Route::is_loginned() || $controller_name === 'authorize') {

            $model_name = "Model_" . $controller_name;
            $controller_name = "Controller_" . $controller_name;
            $action_name = "action_" . $action_name;

            $model_file = strtolower($model_name) . ".php";
            $model_path = "application/models/" . $model_file;

            if (file_exists(($model_path))) {
                include "application/models/" . $model_file;
            }

            $controller_file = strtolower($controller_name) . ".php";
            $controller_path = "application/controllers/" . $controller_file;

            if (file_exists($controller_path)) {
                include "application/controllers/" . $controller_file;
            } else {
                    Route::ErrorPage404();
            }

            $controller = new $controller_name;
            $action = $action_name;

            if (method_exists($controller, $action)) {
                $controller->$action();
            } else {

                    Route::ErrorPage404();
            }
        } else {
            header("Location: /main/authorize");
        }
    }

    static function is_loginned()
    {
        session_start();
        if (isset($_SESSION['id_user']) and isset($_SESSION['login_user'])) {
            return true;
        } else if(isset($_COOKIE['id_user']) and isset($_COOKIE['code_user'])){

            $host = "localhost";
            $user = "root";
            $password = "root";
            $base = "cash_statistic";

            $mysqli = new mysqli($host, $user, $password, $base);

            $result = $mysqli->query("SELECT * FROM session WHERE id_user = '{$_COOKIE['id_user']}'");

            if($row = $result->fetch_assoc()){
                if($row['id_user'] === $_COOKIE['id_user'] and $row['code_user'] === $_COOKIE['code_user']){
                    $_SESSION['id_user'] = $row['id_user'];
                    $_SESSION['login_user'] = $row['login_user'];

                    setcookie('id_user', $_SESSION['id_user'], time() + 60*60*24, '/main/');
                    setcookie('code_user', $row['code_user'], time() + 60*60*24, '/main/');
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    function ErrorPage404()
    {
        $host = "http://" . $_SERVER["HTTP_HOST"] . "/>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>";
        header("HTTP/1.1 404 Not Found");
        header("Status: 404 Not Found");
        header("Location:" . $host . "404");
    }

    function get_connection()
    {
        $host = "localhost";
        $user = "root";
        $password = "root";
        $base = "cash_statistic";

        $connection = new mysqli($host, $user, $password, $base);
        if ($connection->connect_errno) {
            echo "Failed to connect to MySQL: (" . $connection->connect_errno . ") " . $connection->connect_error;
        }
        $connection;

    }
}