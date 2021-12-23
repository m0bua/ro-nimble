<?php

return [

    'goods' => [
        'endpoint_url' => env('GQL_GOODS_SERVICE_URL', ''),
        'authorization_headers' => [
            'Authorization' => 'Basic '. base64_encode(env('GQL_GOODS_SERVICE_LOGIN', '') . ':' . env('GQL_GOODS_SERVICE_PASSWORD', ''))
        ],
        'http_options' => [],
    ]

];
