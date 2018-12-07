**This was created in 2 hours and has not been tested.  I can't even guarantee there's a need beyond what `beyondcode/laravel-websockets` provides natively.  It goes without saying that this exists without warranty of success.**

# How to install:
1. Run `composer require tenancy/laravel-websockets-bridge`
   * You should already have `hyn/multi-tenant` and `beyondcode/laravel-websockets` installed following each package's documented install process.
2. In the `config/websockets.php` file, update the `statistics` -> `model` key to have a value of `\Tenancy\LaravelWebsockets\Models\WebsocketsStatisticsEntry::class`
   * This ensures the model uses the `system` database connection, even if you have `tenant` as default.
