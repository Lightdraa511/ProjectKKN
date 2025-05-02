<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\User;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Membuat transaksi Snap Midtrans
     *
     * @param User $user
     * @param float $amount
     * @return array
     */
    public function createTransaction(User $user, float $amount)
    {
        $orderId = 'KKN-' . $user->id . '-' . time();

        $transaction = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'item_details' => [
                [
                    'id' => 'KKN-REG',
                    'price' => $amount,
                    'quantity' => 1,
                    'name' => 'Pendaftaran KKN Universitas Langlangbuana',
                ]
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($transaction);

            // Simpan data payment
            $payment = Payment::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'payment_method' => 'midtrans',
                'status' => 'pending',
                'transaction_id' => $orderId,
            ]);

            return [
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $orderId,
                'payment' => $payment
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Memproses callback notifikasi dari Midtrans
     *
     * @param array $notification
     * @return bool
     */
    public function processNotification(array $notification)
    {
        $orderId = $notification['order_id'];
        $payment = Payment::where('transaction_id', $orderId)->first();

        if (!$payment) {
            return false;
        }

        $transactionStatus = $notification['transaction_status'];
        $fraudStatus = isset($notification['fraud_status']) ? $notification['fraud_status'] : null;

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'challenge') {
                $payment->status = 'challenge';
            } else if ($fraudStatus === 'accept') {
                $payment->status = 'success';
                $this->updateUserStatus($payment->user);
            }
        } else if ($transactionStatus === 'settlement') {
            $payment->status = 'success';
            $payment->payment_date = now();
            $this->updateUserStatus($payment->user);
        } else if ($transactionStatus === 'cancel' || $transactionStatus === 'deny' || $transactionStatus === 'expire') {
            $payment->status = 'failed';
        } else if ($transactionStatus === 'pending') {
            $payment->status = 'pending';
        }

        $payment->save();

        // Log setelah update status
        \Illuminate\Support\Facades\Log::info('Payment Status Updated', [
            'order_id' => $orderId,
            'status' => $payment->status,
            'user_id' => $payment->user_id
        ]);

        return true;
    }

    /**
     * Update status user setelah pembayaran berhasil
     *
     * @param User $user
     * @return void
     */
    private function updateUserStatus(User $user)
    {
        $user->payment_status = true;
        $user->registration_status = 'payment_completed';
        $user->save();
    }
}
