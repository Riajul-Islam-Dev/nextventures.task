<?php

namespace App\Repositories\API;

use App\Models\Payment;

class PaymentRepository implements PaymentRepositoryInterface
{
    public function createPayment(array $data)
    {
        return Payment::create($data);
    }

    public function findPaymentById($id)
    {
        return Payment::find($id);
    }

    public function updatePaymentStatus($id, $status)
    {
        $payment = $this->findPaymentById($id);
        if ($payment) {
            $payment->status = $status;
            $payment->save();
            return $payment;
        }
        return null;
    }
}
