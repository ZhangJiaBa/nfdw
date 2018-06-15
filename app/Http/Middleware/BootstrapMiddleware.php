<?php

namespace App\Http\Middleware;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Illuminate\Http\Request;

class BootstrapMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        Form::registerBuiltinFields();

        if (file_exists($bootstrap = admin_path('bootstrap.php'))) {
            require $bootstrap;
        }

        //TODO-FIXED
        Form::forget(['map', 'editor']);
        app('view')->prependNamespace('admin', resource_path('views/base'));

        Form::collectFieldAssets();

        Grid::registerColumnDisplayer();

        return $next($request);
    }
}
