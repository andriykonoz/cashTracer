<?php

class Controller_Authorize extends Controller
{

    function __construct()
    {
        $this->model = new Model_Authorize();
        $this->view = new View();
    }

    function action_index()
    {
        $this->view->generate("authorize_view.php");
    }

    function action_login()
    {
        $this->model->login();
    }

    function action_logout()
    {
        $this->model->loguot();
    }

    function action_reg()
    {
        $this->view->generate("reg_view.php");
    }

    function action_reguser()
    {

        echo json_encode($this->model->reg_user());
    }

    function action_recover()
    {
        $this->view->generate("recovery_view.php");
    }

    function action_recoverpassword()
    {
        echo json_encode($this->model->restore_password());
    }

}