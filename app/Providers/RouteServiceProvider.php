<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $dateRegex = '\d{2,4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[0-1])'; // (YY|YYYY)-MM-DD
        $timeRegex = '(\d|[01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])'; // HH:MM:SS
        $regex = function ($r) {
            return '^' . $r . '$';
        };

        $patterns = [
            'email' => '^[\w\-]+@[\w]+([\.]?[aA-zZ])+\.[aA-zZ]{2,3}$',
            'date' => $regex($dateRegex),
            'time' => $regex($timeRegex),
            'datetime' => $regex($dateRegex . '\s' . $timeRegex),
            'hp' => '^(01[0-9])\d{8}$',
            'name' => '^[가-힣\w]{2,}$'
        ];

        Route::patterns($patterns);

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
}
