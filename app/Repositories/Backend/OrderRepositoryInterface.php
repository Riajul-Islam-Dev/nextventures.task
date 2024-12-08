<?php

namespace App\Repositories\Backend;

use App\Models\Order;

interface OrderRepositoryInterface
{
    public function getAllOrders();
    public function findOrderById($id);
    public function createOrder(array $data);
    public function updateOrder($id, array $data);
    public function deleteOrder($id);
}
