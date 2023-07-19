<?php

namespace Controller\Sample\SubSample;

use Controller\BaseController;

class SubFooController extends BaseController
{
    function getSubFoo()
    {
        echo 'HTTP REQUEST : GET /subFoo <br/>';
        echo 'PARAMETERS : <br/>';
        echo 'subFoo : ' . $this->parameterMap['subFoo'] . '<br/>';
    }
}