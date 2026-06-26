<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\RateLimiter;
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
        
        Carbon::setLocale('id');

        // ==================== Rate Limiter ====================

        RateLimiter::for('bed-info', function (Request $request) {

            return Limit::perMinute(10)
                ->by($request->ip())
                ->response(function () {

                    return response()->json([
                        'success' => false,
                        'message' => 'Terlalu banyak request. Silakan coba lagi dalam 1 menit.',
                        'data' => []
                    ], 429);

                });
        });
    }
}
