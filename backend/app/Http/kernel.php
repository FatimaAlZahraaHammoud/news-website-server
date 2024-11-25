<?php 

namespace App\Http;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class kernel extends HttpKernel
{

    protected $routeMiddleware = [
        'jwt.auth' => \App\Http\Middleware\JwtMiddleware::class,
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
    ];

}

?>