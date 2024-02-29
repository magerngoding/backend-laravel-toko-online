<?php
return [
    'mercant_id' => env('MIDTRANS_MERCHAT_ID'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'server_key' => env('MIDTRANS_SERVER_KEY'),

    // jangan lupa make sure jika pake production/sandbox
    'is_production' => false,
    'is_sanitized' => false,
    'is_3ds' => false,
];
