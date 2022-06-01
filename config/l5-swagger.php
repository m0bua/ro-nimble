<?php

return [
    'default' => 'v1',
    'documentations' => [
        'v1' => [
            'api' => [
                'title' => 'L5 Swagger UI',
            ],

            'routes' => [
                /*
                 * Route for accessing api documentation interface
                */
                'api' => 'api/v1/documentation',
                'docs' => 'api/v1/docs',
                'oauth2_callback' => 'api/v1/callback',
            ],
            'paths' => [
                /*
                 * Absolute path to location where parsed annotations will be stored
                */
                'docs' => env('L5_SWAGGER_DOCS', storage_path('api-docs')),

                /*
                 * Edit to include full URL in ui for assets
                */
                'use_absolute_path' => env('L5_SWAGGER_USE_ABSOLUTE_PATH', true),

                /*
                 * File name of the generated json documentation file
                */
                'docs_json' => 'api-docs.json',

                /*
                 * File name of the generated YAML documentation file
                */
                'docs_yaml' => 'api-docs.yaml',

                /*
                * Set this to `json` or `yaml` to determine which documentation file to use in UI
                */
                'format_to_use_for_docs' => env('L5_FORMAT_TO_USE_FOR_DOCS', 'json'),

                /*
                 * Edit to set path where swagger ui assets should be stored
                */
                'swagger_ui_assets_path' => env('L5_SWAGGER_UI_ASSETS_PATH', 'vendor/swagger-api/swagger-ui/dist/'),

                /*
                 * Absolute paths to directory containing the swagger annotations are stored.
                */
                'annotations' => [
                    base_path('app'),
                ],
            ],
            /*
             * Set this to `true` in development mode so that docs would be regenerated on each request
             * Set this to `false` to disable swagger generation on production
            */
            'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', false),
        ],
    ],
];
