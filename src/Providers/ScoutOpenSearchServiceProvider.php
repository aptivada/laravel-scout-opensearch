<?php

declare(strict_types=1);

namespace SoeurngSar\LaravelScoutOpenSearch\Providers;

use SoeurngSar\LaravelScoutOpenSearch\OpenSearch\EloquentHitsIteratorAggregate;
use SoeurngSar\LaravelScoutOpenSearch\OpenSearch\HitsIteratorAggregate;
use Illuminate\Support\ServiceProvider;
use Laravel\Scout\ScoutServiceProvider;
use SoeurngSar\LaravelScoutOpenSearch\Console\Commands\FlushCommand;
use SoeurngSar\LaravelScoutOpenSearch\Console\Commands\ImportCommand;
use SoeurngSar\LaravelScoutOpenSearch\Searchable\DefaultImportSourceFactory;
use SoeurngSar\LaravelScoutOpenSearch\Searchable\ImportSourceFactory;
use OpenSearch\Client;

final class ScoutOpenSearchServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerCommands();
    }

    public function register(): void
    {
        $this->app->bind(
            HitsIteratorAggregate::class,
            EloquentHitsIteratorAggregate::class
        );
        $this->app->register(ScoutServiceProvider::class);
        $this->app->bind(ImportSourceFactory::class, DefaultImportSourceFactory::class);
    }

    private function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ImportCommand::class,
                FlushCommand::class,
            ]);
        }
    }

    public function provides(): array
    {
        return [Client::class];
    }
}
