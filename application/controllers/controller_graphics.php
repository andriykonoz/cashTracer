<?php

class Controller_Graphics extends Controller {

    function __construct(){
        $this->model = new Model_Graphics();
        $this->view = new View();
    }

    function action_index(){
        $data = $this->model->get_data();
        $this->view->generate("graphics_view.php", $data);
    }

    function action_build(){
        echo $this->model->get_graphics_json_data();
    }
}