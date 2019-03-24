<?php

namespace Jchegenye\MyPesaPal;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
use Jchegenye\MyPesaPal\JTech\PesaPalIframe;

class MyPesaPalServiceProvider extends ServiceProvider
{
    
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $this->publishes([
            __DIR__.'/config/pesapal.php' => config_path('pesapal.php'),
        ]);

        Response::macro('pesapal', function ($request) {

            $func = new PesaPalIframe();
            $data = $func->processFormData($request);

            return $data;

        });

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->mergeConfigFrom(
            __DIR__.'/config/pesapal.php', 'pesapal'
        );
        
    }
}
