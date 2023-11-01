<?php

namespace SoeurngSar\LaravelScoutOpenSearch\Searchable;

interface ImportSourceFactory
{
    public static function from(string $className): ImportSource;
}
