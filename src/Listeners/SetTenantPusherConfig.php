<?php

namespace Tenancy\LaravelWebsockets\Listeners;

use Hyn\Tenancy\Abstracts\WebsiteEvent;
use Hyn\Tenancy\Environment;
use Hyn\Tenancy\Events\Websites\Identified;

class SetTenantPusherConfig
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(WebsiteEvent $event)
    {
        if ($event->website != null) {
            $uuid = $event->website->uuid;

            app('config')->set('broadcasting.connections.pusher.app_id', $uuid);
            app('config')->set('broadcasting.connections.pusher.key', $uuid);
            app('config')->set('broadcasting.connections.pusher.secret', app('encrypter')->encrypt($uuid));
        }
    }
}