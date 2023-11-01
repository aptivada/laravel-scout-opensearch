<?php

namespace SoeurngSar\LaravelScoutOpenSearch\Providers;

use Illuminate\Pagination\CursorPaginator;
use Laravel\Scout\Builder;
use OpenSearchDSL\Sort\FieldSort;
use SoeurngSar\LaravelScoutOpenSearch\Engines\OpenSearchEngine;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Laravel\Scout\EngineManager;
use OpenSearch\Client;
use OpenSearch\ClientBuilder;

class OpenSearchServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/opensearch.php', 'opensearch');
    }

    public function boot()
    {
        $this->publishes([
           __DIR__.'/../../config/opensearch.php' => config_path('opensearch.php'),
        ], 'opensearch-config');

        $this->app->make(EngineManager::class)->extend(OpenSearchEngine::class, function (Application $app) {
            $opensearch = $app->make(Client::class);

            return new OpenSearchEngine($opensearch);
        });

        $this->app->singleton(Client::class, function () {
            if (env('OPENSEARCH_HOST_PROVIDER') === 'aws') {
                return ClientBuilder::fromConfig(config('opensearch.clients.aws'));
            } else {
                return ClientBuilder::fromConfig(config('opensearch.clients.local'));
            }
        });

        Builder::macro(
            'cursorPaginate',
            function (int $perPage = null, string $cursorName = 'cursor', $cursor = null): CursorPaginator {
            /**
             * @var Builder $this
             */
                $perPage = $perPage ?: $this->model->getPerPage();

                return $this->engine()->cursorPaginate($this, $perPage, $cursorName, $cursor);
            }
        );

        Builder::macro('orderByRaw', function (FieldSort $sort) {
            /**
             * @var Builder $this
             */
            $this->orders[] = $sort;

            return $this;
        });
    }
}
