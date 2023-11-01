<?php

namespace SoeurngSar\LaravelScoutOpenSearch\Jobs;

use OpenSearch\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Collection;
use SoeurngSar\LaravelScoutOpenSearch\ProgressReportable;
use SoeurngSar\LaravelScoutOpenSearch\Searchable\ImportSource;

/**
 * @internal
 */
final class Import
{
    use Queueable;
    use ProgressReportable;

    /**
     * @var ImportSource
     */
    private $source;

    public ?int $timeout = null;

    /**
     * @param  ImportSource  $source
     */
    public function __construct(ImportSource $source)
    {
        $this->source = $source;
    }

    /**
     * @param  Client  $opensearch
     */
    public function handle(Client $opensearch): void
    {
        $stages = $this->stages();
        $estimate = $stages->sum->estimate();
        $this->progressBar()->setMaxSteps($estimate);
        $stages->each(function ($stage) use ($opensearch) {
            $this->progressBar()->setMessage($stage->title());
            $stage->handle($opensearch);
            $this->progressBar()->advance($stage->estimate());
        });
    }

    private function stages(): Collection
    {
        return ImportStages::fromSource($this->source);
    }
}
