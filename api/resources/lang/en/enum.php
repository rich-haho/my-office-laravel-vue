<?php

return [
    'bookingstatus'   => [
        'values'    => [
            'pending'   => 'Your order is in process',
            'prepaid'   => 'Your order is awaiting a confirmation',
            'paid'      => 'Your order has been paid and is being processed',
            'accepted'  => 'Your order is accepted and is being processed',
            'processed' => 'Your order will soon be delivered',
            'refused'   => 'Your order is refused',
            'delivered' => 'Your order has been delivered'
        ],
        'names'    => [
            'pending'   => 'PENDING',
            'prepaid'   => 'PREPAID',
            'paid'      => 'PAID',
            'accepted'  => 'ACCEPTED',
            'processed' => 'PROCESSED',
            'refused'   => 'REFUSED',
            'delivered' => 'DELIVERED'
        ],
    ],
];
