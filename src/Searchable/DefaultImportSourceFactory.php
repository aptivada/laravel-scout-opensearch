<?php

namespace SoeurngSar\LaravelScoutOpenSearch\Searchable;

final class DefaultImportSourceFactory implements ImportSourceFactory
{
    public static function from(string $className): ImportSource
    {
        return new DefaultImportSource($className);
    }
}
