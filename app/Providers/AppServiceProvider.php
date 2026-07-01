<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        Blade::directive('money', function ($expression) {
            return "<?php echo 'Rp ' . number_format($expression, 0, ',', '.'); ?>";
        });

        Blade::if('roleIs', function (string $role) {
            return auth()->check() && auth()->user()->hasRole($role);
        });

        Blade::if('roleIn', function (array $roles) {
            return auth()->check() && auth()->user()->hasAnyRole($roles);
        });
    }
}
