<?php

namespace SoeurngSar\LaravelScoutOpenSearch\OpenSearch;

interface HitsIteratorAggregate extends \IteratorAggregate
{
    public function __construct(array $results, callable $callback = null);

    public function getIterator();
}
