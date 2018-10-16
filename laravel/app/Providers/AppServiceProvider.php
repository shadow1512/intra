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

        Blade::directive('convertdate', function ($expression) {
            $months =   array("января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря");
            $month  =   $months[date('n', strtotime($expression))-1];
            return "<?php echo date('j', strtotime($expression)) . ' ' . $month; ?>";
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
