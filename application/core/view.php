<?php

/**
 * Created by PhpStorm.
 * User: Roland
 * Date: 8/30/2015
 * Time: 12:42 PM
 */
class View
{
    function generate($content_view, $data = null)
    {
        include "application/views/" . $content_view;
    }


}