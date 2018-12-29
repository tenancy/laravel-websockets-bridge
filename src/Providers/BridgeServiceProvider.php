<?php
namespace Tenancy\LaravelWebsockets\Providers;

use BeyondCode\LaravelWebSockets\WebSocketsServiceProvider;
use Hyn\Tenancy\Events\Websites\Identified;
use Hyn\Tenancy\Events\Websites\Switched;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Tenancy\LaravelWebsockets\Listeners\SetTenantPusherConfig;

class BridgeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(WebSocketsServiceProvider::class);
    }

    public function boot()
    {
        Event::listen(Identified::class, SetTenantPusherConfig::class);
        Event::listen(Switched::class, SetTenantPusherConfig::class);
    }
}
