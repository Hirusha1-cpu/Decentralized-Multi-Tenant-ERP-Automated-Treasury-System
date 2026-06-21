<?php

return [
    // 1. Alchemy හෝ Infura වෙතින් ලැබෙන RPC Endpoint එක
    'rpc_url' => env('WEB3_RPC_URL', 'https://alchemy.com'),

    // 2. බ්ලොක්චේන් එකේ තියෙන ප්‍රධාන මව් Factory Contract Address එක
    'factory_address' => env('WEB3_FACTORY_ADDRESS', '0x0000000000000000000000000000000000000000'),
];
