<?php

return [
    "status" => [
        1 => "Pending",
        2 => "Processing",
        3 => "Shipping",
        4 => "Delivred",
        5 => "Returned",
    ],

    "shipping_methods" => [
        1 => ["amount" => 0, "title" => "Pickup from store"],
        2 => ["amount" => 0.05, "title" => "Home delivery"],
    ],

    "payment_methods" => [
        1 => "Cash On Delivery",
        2 => "Pay At Store"
    ],
];
