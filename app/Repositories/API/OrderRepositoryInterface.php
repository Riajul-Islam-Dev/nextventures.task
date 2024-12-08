<?php

namespace App\Repositories\API;

interface OrderRepositoryInterface
{
    public function createOrder($user, $productId, $quantity);
    public function getOrdersByUser($user);
}
