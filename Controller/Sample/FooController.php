<?php

namespace Controller\Sample;

use Controller\BaseController;
use Model\Sample\FooModel;

class FooController extends BaseController
{
    function getFoo()
    {
        echo 'HTTP REQUEST : GET /foo <br/>';
        echo 'PARAMETERS : <br/>';
        echo 'foo : ' . $this->parameterMap['foo'] . '<br/>';

        $foo = new FooModel();
        $foo->getFoo($this->parameterMap['foo']);
    }

    function getLogin()
    {
        echo 'HTTP REQUEST : GET /login <br/>';
        echo 'PARAMETERS : <br/>';
        echo '[EMPTY]';
    }

    function getBar()
    {
        echo 'HTTP REQUEST : GET /foo/{fooId}/bar/?{barId} <br/>';
        echo 'PARAMETERS : <br/>';
        echo 'foo : ' . $this->parameterMap['foo'] . '<br/>';
        echo 'bar : ' . $this->parameterMap['bar'] . '<br/>';
    }

    function getBarBaz()
    {
        echo 'HTTP REQUEST : GET /foo/{fooId}/bar/{barId}/baz/?{bazId} <br/>';
        echo 'PARAMETERS : <br/>';
        echo 'foo : ' . $this->parameterMap['foo'] . '<br/>';
        echo 'bar : ' . $this->parameterMap['bar'] . '<br/>';
        echo 'baz : ' . $this->parameterMap['baz'] . '<br/>';
    }

    function deleteFoo()
    {
        echo 'HTTP REQUEST : DELETE /foo <br/>';
        echo 'PARAMETERS : <br/>';
        echo 'foo : ' . $this->parameterMap['foo'] . '<br/>';

        $foo = new FooModel();
        $foo->deleteFoo($this->parameterMap['foo']);
    }

    function deleteBar()
    {
        echo 'HTTP REQUEST : DELETE /foo/{fooId}/bar/?{barId} <br/>';
        echo 'PARAMETERS : <br/>';
        echo 'foo : ' . $this->parameterMap['foo'] . '<br/>';
        echo 'bar : ' . $this->parameterMap['bar'] . '<br/>';
    }

    function patchFoo()
    {
        echo 'HTTP REQUEST : PATCH /foo <br/>';
        echo 'PARAMETERS : <br/>';
        echo 'foo : ' . $this->parameterMap['foo'] . '<br/>';
    }

    function patchBar()
    {
        echo 'HTTP REQUEST : PATCH /foo/{fooId}/bar/?{barId} <br/>';
        echo 'PARAMETERS : <br/>';
        echo 'foo : ' . $this->parameterMap['foo'] . '<br/>';
        echo 'bar : ' . $this->parameterMap['bar'] . '<br/>';
    }

    function putFoo()
    {
        echo 'HTTP REQUEST : PUT /foo <br/>';
        echo 'PARAMETERS : <br/>';
        echo 'foo : ' . $this->parameterMap['foo'] . '<br/>';
    }

    function putBar()
    {
        echo 'HTTP REQUEST : PUT /foo/{fooId}/bar/?{barId} <br/>';
        echo 'PARAMETERS : <br/>';
        echo 'foo : ' . $this->parameterMap['foo'] . '<br/>';
        echo 'bar : ' . $this->parameterMap['bar'] . '<br/>';
    }

    function postFoo()
    {
        echo 'HTTP REQUEST : POST /foo <br/>';
        echo 'PARAMETERS : <br/>';
        echo 'foo : ' . $this->parameterMap['foo'] . '<br/>';
        echo 'BODY CONTENT : <br/>';
        echo '<pre>';
        var_dump($this->bodyContent);
        echo '</pre>';

        $foo = new FooModel();
        $foo->createFoo($this->bodyContent);
    }

    function postBar()
    {
        echo 'HTTP REQUEST : POST /foo/{fooId}/bar/?{barId} <br/>';
        echo 'PARAMETERS : <br/>';
        echo 'foo : ' . $this->parameterMap['foo'] . '<br/>';
        echo 'bar : ' . $this->parameterMap['bar'] . '<br/>';
    }
}