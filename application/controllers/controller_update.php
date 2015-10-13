<?php

class Controller_Update extends  Controller
{
    function  __construct(){
        $this->model = new Model_Update();
        $this->view = new View();
    }


    function action_index(){
        $data = $this->model->set_data();
    }


}