<?php

namespace SoeurngSar\LaravelScoutOpenSearch\Jobs\Stages;

use OpenSearch\Client;
use SoeurngSar\LaravelScoutOpenSearch\OpenSearch\DefaultAlias;
use SoeurngSar\LaravelScoutOpenSearch\OpenSearch\FilteredAlias;
use SoeurngSar\LaravelScoutOpenSearch\OpenSearch\Index;
use SoeurngSar\LaravelScoutOpenSearch\OpenSearch\Params\Indices\Create;
use SoeurngSar\LaravelScoutOpenSearch\OpenSearch\WriteAlias;
use SoeurngSar\LaravelScoutOpenSearch\Searchable\ImportSource;

/**
 * @internal
 */
final class CreateWriteIndex
{
    /**
     * @var ImportSource
     */
    private $source;
    /**
     * @var Index
     */
    private $index;

    /**
     * @param  ImportSource  $source
     * @param  Index  $index
     */
    public function __construct(ImportSource $source, Index $index)
    {
        $this->source = $source;
        $this->index = $index;
    }

    public function handle(Client $elasticsearch): void
    {
        $source = $this->source;
        $this->index->addAlias(
            new FilteredAlias(
                new WriteAlias(new DefaultAlias($source->searchableAs())),
                $this->index
            )
        );

        $params = new Create(
            $this->index->name(),
            $this->index->config()
        );

        $elasticsearch->indices()->create($params->toArray());
    }

    public function title(): string
    {
        return 'Create write index';
    }

    public function estimate(): int
    {
        return 1;
    }
}
