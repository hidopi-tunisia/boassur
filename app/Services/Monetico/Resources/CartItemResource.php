<?php

namespace App\Services\Monetico\Resources;

class CartItemResource extends Ressource
{
    protected $keys = [
        'name',
        'description',
        'productCode',
        'imageURL',
        'unitPrice',
        'quantity',
        'productSKU',
        'productRisk',
    ];
}
