<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Access Token Expiration Time
    |--------------------------------------------------------------------------
    |
    | This value determines how long access tokens issued by Passport will
    | remain valid. You can override this via the ACCESS_TOKEN_EXPIRE_MINUTES
    | environment variable without changing this file.
    |
    */

    'access_token_expire_minutes' => env('ACCESS_TOKEN_EXPIRE_MINUTES', 30),

];
