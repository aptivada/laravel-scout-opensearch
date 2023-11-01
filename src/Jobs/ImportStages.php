<?php

namespace SoeurngSar\LaravelScoutOpenSearch\Jobs;

use Illuminate\Support\Collection;
use SoeurngSar\LaravelScoutOpenSearch\OpenSearch\Index;
use SoeurngSar\LaravelScoutOpenSearch\Jobs\Stages\CleanUp;
use SoeurngSar\LaravelScoutOpenSearch\Jobs\Stages\CreateWriteIndex;
use SoeurngSar\LaravelScoutOpenSearch\Jobs\Stages\PullFromSource;
use SoeurngSar\LaravelScoutOpenSearch\Jobs\Stages\RefreshIndex;
use SoeurngSar\LaravelScoutOpenSearch\Jobs\Stages\SwitchToNewAndRemoveOldIndex;
use SoeurngSar\LaravelScoutOpenSearch\Searchable\ImportSource;

class ImportStages extends Collection
{
    /**
     * @param  ImportSource  $source
     * @return Collection
     */
    public static function fromSource(ImportSource $source) : Collection
    {
        $index = Index::fromSource($source);

        return (new self([
            new CleanUp($source),
            new CreateWriteIndex($source, $index),
            PullFromSource::chunked($source),
            new RefreshIndex($index),
            new SwitchToNewAndRemoveOldIndex($source, $index),
        ]))->flatten()->filter();
    }
}
