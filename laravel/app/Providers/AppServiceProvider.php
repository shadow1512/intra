<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use DB;
use Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //array('января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря');
        Schema::defaultStringLength(191);//

        DB::listen(function($query) {
            Log::info(
                $query->sql,
                $query->bindings,
                $query->time
            );
        });

        Blade::directive('convertdate', function ($expression, $months = "") {
            return "<?php echo date('j', strtotime($expression)) . ' ' . date('M', strtotime($expression)) . ' ' . date('Y', strtotime($expression)) . ' года'; ?>";
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
