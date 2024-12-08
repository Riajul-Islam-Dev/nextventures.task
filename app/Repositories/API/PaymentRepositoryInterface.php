<?php

namespace App\Repositories\API;

interface PaymentRepositoryInterface
{
    public function createPayment(array $data);
    public function findPaymentById($id);
    public function updatePaymentStatus($id, $status);
}
