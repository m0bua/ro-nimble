<?php

return [
    'settings' => [
        'max_ngram_diff' => 15,
        'index' => [
            'analysis' =>  [
                'analyzer' =>  [
                    'edge_title_analyzer' => [
                        'type' => 'custom',
                        'tokenizer' => 'edge_title_tokenizer',
                        'filter' => ['lowercase']
                    ],
                    'title_analyzer' => [
                        'type' => 'custom',
                        'tokenizer' => 'title_tokenizer',
                        'filter' => ['lowercase']
                    ],
                    'search_title_analyzer' => [
                        'type' => 'custom',
                        'tokenizer' => 'whitespace',
                        'filter' =>  ['lowercase']
                    ]
                ],
                'tokenizer' =>  [
                    'edge_title_tokenizer' => [
                        'type' => 'edge_ngram',
                        'min_gram' => 1,
                        'max_gram' => 15
                    ],
                    'title_tokenizer' => [
                        'type' => 'ngram',
                        'min_gram' => 1,
                        'max_gram' => 15
                    ]
                ]
            ]
        ]
    ],
    'mappings' => [
        'properties' =>  [
            'id' =>  [
                'type' => 'integer'
            ],
            'name' =>  [
                'type' => 'keyword'
            ],
            'title' =>  [
                'type' => 'text',
                'analyzer' => 'title_analyzer',
                'search_analyzer' => 'search_title_analyzer',
                'fields' =>  [
                    'edge' =>  [
                        'type' => 'text',
                        'analyzer' => 'edge_title_analyzer',
                        'search_analyzer' => 'search_title_analyzer'
                    ],
                    'keyword' =>  [
                        'type' => 'keyword',
                        'ignore_above' => 256
                    ]
                ]
            ],
            'first_symbol' =>  [
                'type' => 'keyword'
            ],
            'status' =>  [
                'type' => 'keyword'
            ]
        ]
    ]
];
