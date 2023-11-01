<?php

return [
    'clients' => [
        'aws'  => [
            'hosts' => explode(',', env('SEARCHENGINE_HOSTS', 'opensearch:9200')),
            'sigV4Region' => env('AWS_DEFAULT_REGION', 'ap-southeast-2') ,
            'sigV4Service' => env('AWS_DEFAULT_SERVICE', 'es') ,
            'sigV4CredentialProvider' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ],
        'local'  => [
            'hosts' => explode(',', env('SEARCHENGINE_HOSTS', 'opensearch:9200')),
            'basicAuthentication' => [
                env('SEARCHENGINE_USERNAME', 'admin'),
                env('SEARCHENGINE_PASSWORD', 'admin'),
            ]
        ]
    ],
    'indices' => [
        'mappings' => [
            'default' => [
                'properties' => [
                    'id' => [
                        'type' => 'keyword',
                    ],
                ],
            ],
        ],
        'settings' => [
            'default' => [
                'number_of_shards' => 1,
                'number_of_replicas' => 0,
            ],
        ],
    ],
];
