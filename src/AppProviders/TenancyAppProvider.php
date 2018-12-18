<?php
namespace Tenancy\LaravelWebsockets\AppProviders;

use BeyondCode\LaravelWebSockets\Apps\App;
use BeyondCode\LaravelWebSockets\Apps\AppProvider;
use Hyn\Tenancy\Contracts\Website;
use Hyn\Tenancy\Repositories\WebsiteRepository;

class TenancyAppProvider implements AppProvider
{
    public function __construct(WebsiteRepository $repository)
    {
        $this->repository = $repository;
    }
    public function all(): array
    {
        return $this->repository->query()->get()
            ->map(function ($website) {
                return $this->instantiate($website);
            })
            ->toArray();
    }

    public function findById($appId): ?App
    {
        $website = $this->repository->findByUuid($appId);

        return $this->instantiate($website);
    }

    public function findByKey(string $appKey): ?App
    {
        $website = $this->repository->findByUuid($appKey);

        return $this->instantiate($website);
    }

    public function findBySecret(string $appSecret): ?App
    {
        $uuid = app('encrypter')->decrypt($appSecret);
        $website = $this->repository->findByUuid($uuid);

        return $this->instantiate($website);
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

        if ($website->name) {
            $app->setName($website->name);
        }

        return $app;
    }
}
