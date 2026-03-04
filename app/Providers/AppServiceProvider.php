<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

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
        // ==================== Blade directive @role ====================
        Blade::directive('role', function ($roles) {
            return "<?php if (in_array(session('simrs_tipe'), $roles)): ?>";
        });

        Blade::directive('endrole', function () {
            return "<?php endif; ?>";
        });
    }
}
