<?php

namespace Controller\Sample;

use Controller\Controller;

class SampleViewController extends Controller
{
    public function index(): void
    {
        $this->view('sample/index');
    }
}