<?php

namespace Jchegenye\MyPesaPal\Tests;

use Illuminate\Http\Request;
use Orchestra\Testbench\TestCase;
// use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use Jchegenye\MyPesaPal\JTech\PesaPalIframe;

class MyPesaPalTest extends TestCase
{

    protected $iframedata;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function setUp()
    {

        parent::setUp();

        $this->iframedata = new PesaPalIframe;
        
    }

    public function test_sample(){

        
    }

}