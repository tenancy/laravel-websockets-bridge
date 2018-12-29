<?php
namespace Tenancy\LaravelWebsockets\AppProviders;

use BeyondCode\LaravelWebSockets\Apps\App;
use BeyondCode\LaravelWebSockets\Apps\AppProvider;
use Hyn\Tenancy\Contracts\Website;
use Hyn\Tenancy\Environment;
use Hyn\Tenancy\Repositories\WebsiteRepository;
use Illuminate\Database\Eloquent\Builder;

class TenancyAppProvider implements AppProvider
{
    public function __construct(WebsiteRepository $repository)
    {
        $this->repository = $repository;
    }
    public function all(): array
    {
        $all = $this->getRepositoryQuery()->get()
            ->filter(function($item) { return $item->hostnames->isNotEmpty(); })
            ->map(function($item) { return $this->instantiate($item); })
        ;
        return $all->toArray();
    }

    public function findById($appId): ?App
    {
        $website = $this->getRepositoryQuery()->where('uuid', $appId)->first();

        $this->identify($website);

        return $this->instantiate($website);
    }

    public function findByKey(string $appKey): ?App
    {
        $website = $this->getRepositoryQuery()->where('uuid', $appKey)->first();

        $this->identify($website);

        return $this->instantiate($website);
    }

    public function findBySecret(string $appSecret): ?App
    {
        $uuid = app('encrypter')->decrypt($appSecret);

        $website = $this->getRepositoryQuery()->where('uuid', $uuid)->first();

        $this->identify($website);

        return $this->instantiate($website);
    }

    protected function getRepositoryQuery(): Builder
    {
        return $this->repository->query()->with('hostnames');
    }

    protected function identify(Website $website) {
        app(Environment::class)->tenant($website);

        if (config('tenancy.hostname.update-app-url', false)) {
            config([
                'app.url' => sprintf('//%s', optional($website->hostnames[0])->fqdn)
            ]);
        }
    }

    protected function instantiate(Website $website): ?App
    {
        if (! $website) {
            return null;
        }

        $app = new App(
            $website->uuid,
            $website->uuid,
            app('encrypter')->encrypt($website->uuid)
        );

        $app->enableClientMessages();

        if ($website->hostnames[0]->fqdn) {
            $app->setName($website->hostnames[0]->fqdn);
        }

        return $app;
    }
}