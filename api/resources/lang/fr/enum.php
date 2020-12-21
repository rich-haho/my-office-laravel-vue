<?php

return [
    'bookingstatus'   => [
        'values'    => [
            'pending'   => 'Votre commande est en cours de traitement',
            'prepaid'   => 'Votre commande est en attente d\'une confirmation',
            'paid'      => 'Votre commande est payée et est en cours de traitement',
            'accepted'  => 'Votre commande est acceptée et est en cours de traitement',
            'processed' => 'Votre commande est en cours de préparation et vous sera bientôt livrée',
            'refused'   => "Votre commande est refusée",
            'delivered' => 'Votre commande a été livrée.'
        ],
        'names'    => [
            'pending'   => 'EN ATTENTE',
            'prepaid'   => 'PRÉPAYÉ',
            'paid'      => 'PAYÉ',
            'accepted'  => 'ACCEPTÉ',
            'processed' => 'PRÉPARATION',
            'refused'   => 'REFUSÉ',
            'delivered' => 'LIVRÉ'
        ],
    ],
];
