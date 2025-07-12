<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }


    public function boot(): void
    {
          Response::macro('api', function (
            bool $success,
            string $message,
            $data = null,
            int $status = 200
        ) {
            return response()->json([
                'success' => $success,
                'message' => $message,
                'data' => $data,
            ], $status);
        });

        Response::macro('success', function (
            string $message,
            $data = null,
            int $status = 200
        ) {
            return response()->api(true, $message, $data, $status);
        });

        Response::macro('error', function (
            string $message,
            int $status = 400
        ) {
            return response()->api(false, $message, null, $status);
        });
    
    }
}
