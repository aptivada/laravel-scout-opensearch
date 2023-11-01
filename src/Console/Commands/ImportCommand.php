<?php

declare(strict_types=1);

namespace SoeurngSar\LaravelScoutOpenSearch\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use SoeurngSar\LaravelScoutOpenSearch\OpenSearch\Config\Config;
use SoeurngSar\LaravelScoutOpenSearch\Jobs\Import;
use SoeurngSar\LaravelScoutOpenSearch\Jobs\QueueableJob;
use SoeurngSar\LaravelScoutOpenSearch\Searchable\ImportSource;
use SoeurngSar\LaravelScoutOpenSearch\Searchable\ImportSourceFactory;
use SoeurngSar\LaravelScoutOpenSearch\Searchable\SearchableListFactory;

final class ImportCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $signature = 'scout:import {searchable?* : The name of the searchable}';
    /**
     * {@inheritdoc}
     */
    protected $description = 'Create new index and import all searchable into the one';

    /**
     * {@inheritdoc}
     */
    public function handle(): void
    {
        $this->searchableList((array) $this->argument('searchable'))
        ->each(function ($searchable) {
            $this->import($searchable);
        });
    }

    private function searchableList(array $argument): Collection
    {
        return collect($argument)->whenEmpty(function () {
            $factory = new SearchableListFactory(app()->getNamespace(), app()->path());

            return $factory->make();
        });
    }

    private function import(string $searchable): void
    {
        $sourceFactory = app(ImportSourceFactory::class);
        $source = $sourceFactory::from($searchable);
        $job = new Import($source);
        $job->timeout = Config::queueTimeout();

        if (config('scout.queue')) {
            $job = (new QueueableJob())->chain([$job]);
            $job->timeout = Config::queueTimeout();
        }

        $bar = (new ProgressBarFactory($this->output))->create();
        $job->withProgressReport($bar);

        $startMessage = "scout:import start for <comment>$searchable</comment>";
        $this->line($startMessage);

        /* @var ImportSource $source */
        dispatch($job)->allOnQueue($source->syncWithSearchUsingQueue())
            ->allOnConnection($source->syncWithSearchUsing());

        $doneMessage = config('scout.queue')
            ? "scout:import queue successfully for $searchable"
            : "scout:import successfully for $searchable";

        $this->output->success($doneMessage);
    }
}
