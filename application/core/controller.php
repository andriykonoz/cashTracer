<?php

/**
 * Created by PhpStorm.
 * User: Roland
 * Date: 8/30/2015
 * Time: 12:44 PM
 */
class Controller
{
    public $model;
    public $view;

    function __construct()
    {
        $this->view = new View();
    }

    function  action_index()
    {

    }
}