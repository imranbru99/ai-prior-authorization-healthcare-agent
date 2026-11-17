<?php

namespace App\Providers;

use App\Models\PriorAuthRequest;
use App\Policies\PriorAuthRequestPolicy;
use App\Services\ModuleManager;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ModuleManager::class);
    }

    public function boot(): void
    {
        Gate::policy(PriorAuthRequest::class, PriorAuthRequestPolicy::class);
    }
}
