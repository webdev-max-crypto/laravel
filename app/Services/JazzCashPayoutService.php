<?php

namespace App\Services;

class JazzCashPayoutService
{
    public function releasePayment($amount, $mobile)
    {
        // DEMO MODE ONLY
        return [
            'pp_ResponseCode' => '000',
            'pp_ResponseMessage' => 'Payment released (DEMO)'
        ];
    }
}
