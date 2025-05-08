<?php
    return[
        'supported'=>[
            'en','fr','rn','sw'
        ],
        'default'=>env('APP_LOCALE','en'),
        'fallback'=>env('APP_FALLBACK_LOCALE','en')
    ];
