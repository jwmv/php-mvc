<?php

namespace Controller\Sample;

use Controller\BaseController;

class indexController extends BaseController
{
    function get()
    {
        // echo 'HTTP REQUEST : GET /';
        $this->view('sample/index');
    }
}