The package provides the perfect starting point to integrate
OpenSearch into your Laravel application. It is carefully crafted to simplify the usage
of OpenSearch within the [Laravel Framework](https://laravel.com).

It’s built on top of the latest release of [Laravel Scout](https://laravel.com/docs/scout), the official Laravel search
package. Using this package, you are free to take advantage of all of Laravel Scout’s
great features, and at the same time leverage the complete set of OpenSearch’s search experience.


## Features
Don't forget to :star: the package if you like it. :pray:

- Laravel Scout 10.x support
- [Search amongst multiple models](#search-amongst-multiple-models)
- [**Zero downtime** reimport](#zero-downtime-reimport) - it’s a breeze to import data in production.
- [Eager load relations](#eager-load) - speed up your import.
- Import all searchable models at once.
- A fully configurable mapping for each model.
- Full power of OpenSearch in your queries.

## Requirements

- PHP version >= 8.1
- Laravel Framework version >= 10.0.0

## Installation

Use composer to install the package:

`composer require soeurngsar/laravel-scout-opensearch`

Set env variables
```
SCOUT_DRIVER=SoeurngSar\LaravelScoutOpenSearch\Engines\OpenSearchEngine
```

The package uses `\OpenSearch\Client` from official package, but does not try to configure it,
so feel free do it in your app service provider.
But if you don't want to do it right now,
you can use `SoeurngSar\OpenSearchServiceProvider` from the package.
Register the provider, adding to `config/app.php`
```php
'providers' => [
    // Other Service Providers

    \SoeurngSar\LaravelScoutOpenSearch\Providers\OpenSearchServiceProvider::class
],
```
Set `OPENSEARCH_HOST` env variable
```
OPENSEARCH_HOST=host:port
```
or use commas as separator for additional nodes
```
OPENSEARCH_HOST=host:port,host:port
```
```
OPENSEARCH_HOST_PROVIDER=local or aws
```
And publish config example for elasticsearch
`php artisan vendor:publish --tag config`
## Usage

so all the features of thes two packages is available to use. For usage of this application
I recommend you to check out the documentation of
[matchish/laravel-scout-elasticsearch](https://github.com/matchish/laravel-scout-elasticsearch) as it is a fully compartibile with clean documentation.

## License
Laravel Scout OpenSearch is an open-sourced software licensed under the [MIT license](LICENSE.md).

## Credits

This package is a combination of [matchish/laravel-scout-elasticsearch](https://github.com/matchish/laravel-scout-elasticsearch)
and [cloudmediasolutions/Laravel-Scout-OpenSearch](https://github.com/cloudmediasolutions/Laravel-Scout-OpenSearch). I really apreciate for his hard working to bring this package to the community.
