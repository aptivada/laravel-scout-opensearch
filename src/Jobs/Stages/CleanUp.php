<?php

namespace SoeurngSar\LaravelScoutOpenSearch\Jobs\Stages;

use OpenSearch\Client;
use SoeurngSar\LaravelScoutOpenSearch\OpenSearch\Params\Indices\Alias\Get as GetAliasParams;
use SoeurngSar\LaravelScoutOpenSearch\OpenSearch\Params\Indices\Delete as DeleteIndexParams;
use SoeurngSar\LaravelScoutOpenSearch\Searchable\ImportSource;

/**
 * @internal
 */
final class CleanUp
{
    /**
     * @var ImportSource
     */
    private $source;

    /**
     * @param  ImportSource  $source
     */
    public function __construct(ImportSource $source)
    {
        $this->source = $source;
    }

    public function handle(Client $elasticsearch): void
    {
        $source = $this->source;
        $params = GetAliasParams::anyIndex($source->searchableAs());
        try {
            $response = $elasticsearch->indices()->getAlias($params->toArray());
        } catch (\Exception) {
            $response = [];
        }
        foreach ($response as $indexName => $data) {
            foreach ($data['aliases'] as $alias => $config) {
                if (array_key_exists('is_write_index', $config) && $config['is_write_index']) {
                    $params = new DeleteIndexParams((string) $indexName);
                    $elasticsearch->indices()->delete($params->toArray());
                    continue 2;
                }
            }
        }
    }

    public function title(): string
    {
        return 'Clean up';
    }

    public function estimate(): int
    {
        return 1;
    }
}
