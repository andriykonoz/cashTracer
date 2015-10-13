<?php

class Controller_Statistic extends Controller
{

    function __construct()
    {
        $this->view = new View();
        $this->model = new Model_Statistic();
    }

    function action_index()
    {
        $data = $this->model->get_data();
        $this->view->generate("statistic_view.php", $data);

    }


}
