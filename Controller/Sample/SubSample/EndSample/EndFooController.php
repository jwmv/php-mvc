<?php

namespace Controller\Sample\SubSample\EndSample;

use Controller\BaseController;

class EndFooController extends BaseController
{
    function getEndFoo()
    {
        echo 'HTTP REQUEST : GET /endFoo <br/>';
        echo 'PARAMETERS : <br/>';
        echo 'endFoo : ' . $this->parameterMap['endFoo'] . '<br/>';
    }
}