<?php

use Homeful\Notifications\Notifications\PostPaymentBuyerNotification;
use Homeful\Contracts\Transitions\OnboardedToPaid;

return [
    'notifications' => [
        OnboardedToPaid::class => [
            PostPaymentBuyerNotification::class
        ],
    ]
];
