<?php

namespace App\Providers;

use App\CollectionMacros\ToCsv;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('csrf_token', function () {
            return '<input type="hidden" name="<?php echo config(\'app.csrf_token_name\') ?>" value="<?php echo csrf_token(); ?>">';
        });

        Collection::macro('toCsv', app(ToCsv::class)());
    }
}
