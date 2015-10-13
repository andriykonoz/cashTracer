<?php


////* * * * start of backtrace debug code * * *
//$dbt = debug_backtrace();
//echo "<div><br>= = = = = = = = Backtrace = = = = = = = =<br>\n";
//for ( $d_b_t = 0 ; $d_b_t < count($dbt) ; $d_b_t++ ) {
//    if ( $d_b_t == 0 )
//        echo basename( __FILE__ ) . ' is referenced in ';
//    else {
//        echo $dbt[$d_b_t - 1]['file'] . ' is referenced in ';
//    }
//    if ( isset( $dbt[$d_b_t]['file'] ) ) {
//        echo $dbt[$d_b_t]['file'] . ' on line ';
//    }
//    if ( isset( $dbt[$d_b_t]['line'] ) ) {
//        echo $dbt[$d_b_t]['line'] . ' in a "';
//    }
//    if ( isset( $dbt[$d_b_t]['function'] ) ) {
//        echo $dbt[$d_b_t]['function'] . '"<br>' . "\n";
//    }
//}
//echo "<br>= = = = = = = = = = = = = = = = = = = = =<br>\n</div>";
////* * * * end of backtrace debug code * * *


require('connector.php');

class Model_Authorize extends Model
{

    function login()
    {


        $login = $_POST['login'];
        $password = $_POST['password'];

        $mysqli = Connector::get_connection();

        $result = null;
        $statement = $mysqli->prepare("SELECT id_user, passwd_user FROM users WHERE login_user = ?");

        $statement->bind_param("s", $login);
        $statement->bind_result($selected_id, $selected_password);
        $statement->execute();
        $statement->fetch();


        if ($selected_password === $password) {


            session_start();
            $hash = md5($this->generateCode(10));

            $_SESSION['id_user'] = $selected_id;
            $_SESSION['login_user'] = $login;

            $mysqli = Connector::get_connection();

            $result = $mysqli->query("SELECT * FROM session WHERE id_user = {$_SESSION['id_user']}");

            if ($result->fetch_assoc()) {

                $mysqli->query("UPDATE session SET code_sess = '{$hash}' , user_agent_sess =
                    '{$_SERVER['HTTP_USER_AGENT']}' WHERE id_user = {$_SESSION['id_user']}");
            } else {
                $agent = mysqli_real_escape_string($_SERVER['HTTP_USER_AGENT']);
                $mysqli->query("INSERT INTO session (id_user, code_sess, user_agent_sess) VALUES
                ('{$_SESSION['id_user']}', '{$hash}', '{$agent}')");
            }

            setcookie('id_user', $_SESSION['id_user'], time() + 60 * 60 * 24, '/main/');
            setcookie('code_user', $hash, time() + 60 * 60 * 24, '/main/');
            header("Location: /main/");


        } else {

            echo '<br>wrong password<br>';
            echo '<a href="/main/authorize">Try again</a>';

        }


    }



    private function generateCode($length = 6)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;
        while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0, $clen)];
        }
        return $code;
    }

    function loguot()
    {
        session_destroy();
        setcookie('id_user', '', time() - 60 * 60 * 24 * 14);
        setcookie('code_user', '', time() - 60 * 60 * 24 * 14);
        header('Location: /main/');
    }

    function reg_user()
    {
        $data = array();

        $login = $_POST['login'];
        $password = $_POST['password'];
        $email = $_POST['email'];

        $mysqli = Connector::get_connection();
        $statement = $mysqli->prepare("SELECT id_user FROM users WHERE login_user = ?");
        $statement->bind_param('s', $login);
        $statement->bind_result($id_log);
        $statement->execute();
        $statement->fetch();

        if ($id_log) {
            $data['errors'] = "User with login '{$login}' already exist. Try another login";
        } else {
            $mysqli = Connector::get_connection();
            $statement = $mysqli->prepare("SELECT id_user FROM users WHERE mail_user = ?");
            $statement->bind_param('s', $email);
            $statement->bind_result($id);
            $statement->execute();
            $statement->fetch();

            $data['errors'] =  $id;

            if ($id) {
                $data['errors'] = "User with email '{$email}' already exist. if you forgot password
            <a href='#'>click here</a>";
            } else {
                $mysqli = Connector::get_connection();
                $statement = $mysqli->prepare("INSERT INTO users (login_user, passwd_user, mail_user) VALUES (?,?,?)");
                $statement->bind_param('sss', $login, $password, $email);
                $statement->execute();
            }
        }
        return $data;

    }

    function restore_password(){
        $data = array();
        $login = $_POST['login'];
        $email = $_POST['email'];

        $mysqli = Connector::get_connection();
        $statement = $mysqli->prepare("SELECT id_user, mail_user FROM users WHERE login_user = ?");
        $statement->bind_param('s', $login);
        $statement->bind_result($selected_id, $selected_email);
        $statement->execute();
        $statement->fetch();

        $data['trace'] = $selected_email;

        if($email == $selected_email){
            $new_password = $this->generateCode(8);
            $message = "You request password recovery on the web-cite 'Finance account' for account {$login}\n
            Your new password is {$new_password}\n";

            $headers = 'From: andriykonoz@gmail.com' . "\r\n" .
                'Reply-To: andriykonoz@gmail.com' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

            if(mail($email,"Password recovery", $message, $headers)){
                $mysqli = Connector::get_connection();
                $statement = $mysqli->prepare("UPDATE users SET passwd_user=? WHERE id_user=?");
                $statement->bind_param('ss', $new_password, $selected_id);
                $statement->execute();
            } else {
                $data['errors'] = 'Cant send email. Please, contact administrator';
            }
        } else {
            $data['errors'] = 'Incorrect email!';
        }
        return $data;
    }

}