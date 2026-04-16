<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Confirmation link lifetime
    |--------------------------------------------------------------------------
    |
    | Double opt-in links expire after this many days. Subscribers may request
    | a fresh link from the newsletter page or the resend confirmation form.
    |
    */

    'confirmation_ttl_days' => (int) env('NEWSLETTER_CONFIRMATION_TTL_DAYS', 7),

];
