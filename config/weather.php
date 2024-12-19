<?php

return [
    'precipitations' => [
        202 => 'Thunderstorm with heavy rain',
        232 => 'Thunderstorm with heavy drizzle',
        302 => 'Heavy Drizzle',
        502 => 'Heavy Rain',
        522 => 'Heavy shower rain',
        602 => 'Heavy Snow',
        612 => 'Heavy sleet',
        622 => 'Heavy snow shower',
        623 => 'Flurries',
        751 => 'Freezing Fog',
    ],

    'uvi' => [
        6 => [
          'level' => 'high',
          'message' => 'Apply SPF 30+ sunscreen, wear protective clothing, and limit sun exposure.'
        ],
        7 => [
            'level' => 'high',
            'message' => 'Apply SPF 30+ sunscreen, wear protective clothing, and limit sun exposure.'
        ],
        8 => [
            'level' => 'very high',
            'message' => 'Wear SPF 30+, protective clothing, and sunglasses; seek shade frequently.'
        ],
        9 => [
            'level' => 'very high',
            'message' => 'Wear SPF 30+, protective clothing, and sunglasses; seek shade frequently.'
        ],
        10 => [
            'level' => 'very high',
            'message' => 'Wear SPF 30+, protective clothing, and sunglasses; seek shade frequently.'
        ],
        11 => [
            'level' => 'extreme',
            'message' => 'Avoid sun exposure, wear SPF 50+, protective clothing, and stay in the shade.'
        ],
    ],

    'api_key' => env('WEATHERBIT_API_KEY'),
];
