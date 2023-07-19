<?php

namespace Controller\Sample\SubSample;

use Controller\BaseController;

class SubIndexController extends BaseController
{
    function getSubIndex()
    {
        echo 'HTTP REQUEST : GET /subIndex <br/>';
        echo 'PARAMETERS : <br/>';
        echo 'subIndex : ' . $this->parameterMap['subIndex'] . '<br/>';
    }
}