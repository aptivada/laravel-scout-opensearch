<?php

namespace SoeurngSar\LaravelScoutOpenSearch\OpenSearch;

interface Alias
{
    public function name(): string;

    public function config(): array;
}
