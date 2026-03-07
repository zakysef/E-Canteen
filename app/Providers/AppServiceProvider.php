<?php

namespace App\Providers;

use App\Models\Menu;
use App\Policies\MenuPolicy;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Set Carbon locale to Indonesian for translatedFormat() in Blade layouts
        Carbon::setLocale('id');

        // Register MenuPolicy explicitly (backup for auto-discovery)
        Gate::policy(Menu::class, MenuPolicy::class);
    }
}
