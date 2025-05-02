<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Setting;
use App\Models\User;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Midtrans\Transaction;

class PaymentController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Display the payment form.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();

        // Jika sudah bayar, redirect ke dashboard
        if ($user->payment_status) {
            return redirect()->route('dashboard')
                ->with('info', 'Anda sudah melakukan pembayaran.');
        }

        // Ambil biaya pendaftaran dari database
        $registrationFee = Setting::where('key', 'registration_fee')->first();
        $amount = $registrationFee ? $registrationFee->value : 500000;

        // Buat transaksi midtrans
        $result = $this->midtransService->createTransaction($user, $amount);

        if (!$result['success']) {
            return redirect()->route('dashboard')
                ->with('error', 'Terjadi kesalahan: ' . $result['message']);
        }

        return view('payment.index', [
            'snapToken' => $result['snap_token'],
            'order_id' => $result['order_id'],
            'amount' => $amount
        ]);
    }

    /**
     * Process notification callback from Midtrans
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function notification(Request $request)
    {
        try {
            $notification = $request->all();
            Log::info('Midtrans Notification', $notification);

            $result = $this->midtransService->processNotification($notification);

            if ($result) {
                return response()->json(['status' => 'success']);
            }

            return response()->json(['status' => 'failed']);
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle payment finish callback from Midtrans
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function finish(Request $request)
    {
        $orderId = $request->order_id;
        $payment = Payment::where('transaction_id', $orderId)->first();

        if (!$payment) {
            return redirect()->route('dashboard')
                ->with('error', 'Pembayaran tidak ditemukan.');
        }

        // Jika status sudah success, langsung redirect ke locations.index (langsung pilih lokasi)
        if ($payment->status === 'success') {
            return redirect()->route('locations.index')
                ->with('success', 'Pembayaran berhasil. Silakan pilih lokasi KKN Anda.');
        }

        // Cek status transaksi secara real-time dari Midtrans
        try {
            // Set konfigurasi Midtrans
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');

            // Dapatkan status dari Midtrans
            $statusResponse = Transaction::status($orderId);
            Log::info('Midtrans Status Check in Finish Page', ['order_id' => $orderId, 'status' => json_encode($statusResponse)]);

            if (is_object($statusResponse) && isset($statusResponse->transaction_status)) {
                if ($statusResponse->transaction_status === 'settlement' || $statusResponse->transaction_status === 'capture') {
                    // Update status payment dan user
                    $payment->status = 'success';
                    $payment->payment_date = now();
                    $payment->save();

                    $user = $payment->user;
                    $user->payment_status = true;
                    $user->registration_status = 'payment_completed';
                    $user->save();

                    // Redirect ke halaman lokasi
                    return redirect()->route('locations.index')
                        ->with('success', 'Pembayaran berhasil. Silakan pilih lokasi KKN Anda.');
                }
            }
        } catch (\Exception $e) {
            Log::error('Midtrans Status Check Error in Finish Page: ' . $e->getMessage());
        }

        // Jika status masih pending, artinya belum mendapat callback notification
        if ($payment->status === 'pending') {
            return redirect()->route('payment.waiting', ['order_id' => $orderId]);
        }

        // Jika gagal
        return redirect()->route('dashboard')
            ->with('error', 'Pembayaran gagal atau dibatalkan.');
    }

    /**
     * Display waiting page
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function waiting(Request $request)
    {
        $orderId = $request->order_id;
        $payment = Payment::where('transaction_id', $orderId)->first();

        if (!$payment) {
            return redirect()->route('dashboard')
                ->with('error', 'Pembayaran tidak ditemukan.');
        }

        // Jika status sudah success, langsung redirect ke halaman lokasi
        if ($payment->status === 'success') {
            return redirect()->route('locations.index')
                ->with('success', 'Pembayaran berhasil. Silakan pilih lokasi KKN Anda.');
        }

        // Cek status transaksi secara real-time dari Midtrans
        try {
            // Set konfigurasi Midtrans
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');

            // Dapatkan status dari Midtrans
            $statusResponse = Transaction::status($orderId);
            Log::info('Midtrans Status Check in Waiting Page', ['order_id' => $orderId, 'status' => json_encode($statusResponse)]);

            if (is_object($statusResponse) && isset($statusResponse->transaction_status)) {
                if ($statusResponse->transaction_status === 'settlement' || $statusResponse->transaction_status === 'capture') {
                    // Update status payment dan user
                    $payment->status = 'success';
                    $payment->payment_date = now();
                    $payment->save();

                    $user = $payment->user;
                    $user->payment_status = true;
                    $user->registration_status = 'payment_completed';
                    $user->save();

                    // Redirect ke halaman lokasi
                    return redirect()->route('locations.index')
                        ->with('success', 'Pembayaran berhasil. Silakan pilih lokasi KKN Anda.');
                }
            }
        } catch (\Exception $e) {
            Log::error('Midtrans Status Check Error in Waiting Page: ' . $e->getMessage());
        }

        return view('payment.waiting', [
            'payment' => $payment
        ]);
    }

    /**
     * Display payment confirmation page.
     *
     * @return \Illuminate\View\View
     */
    public function callback()
    {
        $user = Auth::user();

        if (!$user->payment_status) {
            return redirect()->route('dashboard');
        }

        // Redirect langsung ke halaman lokasi setelah pembayaran
        return redirect()->route('locations.index')
            ->with('success', 'Pembayaran berhasil. Silakan pilih lokasi KKN Anda.');
    }

    /**
     * Check payment status via AJAX
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkStatus(Request $request)
    {
        $orderId = $request->order_id;
        $payment = Payment::where('transaction_id', $orderId)->first();

        if (!$payment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pembayaran tidak ditemukan'
            ]);
        }

        // Jika status sudah success, langsung return success
        if ($payment->status === 'success') {
            return response()->json([
                'status' => 'success',
                'message' => 'Pembayaran berhasil'
            ]);
        }

        // Cek status transaksi secara real-time dari Midtrans
        try {
            // Set konfigurasi Midtrans
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');

            // Dapatkan status dari Midtrans
            $statusResponse = Transaction::status($orderId);
            Log::info('Midtrans Status Check via AJAX', ['order_id' => $orderId, 'status' => json_encode($statusResponse)]);

            if (is_object($statusResponse) && isset($statusResponse->transaction_status)) {
                if ($statusResponse->transaction_status === 'settlement' || $statusResponse->transaction_status === 'capture') {
                    // Update status payment dan user
                    $payment->status = 'success';
                    $payment->payment_date = now();
                    $payment->save();

                    $user = $payment->user;
                    $user->payment_status = true;
                    $user->registration_status = 'payment_completed';
                    $user->save();

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Pembayaran berhasil',
                        'redirect' => route('locations.index')
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Midtrans Status Check Error via AJAX: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memeriksa status pembayaran'
            ]);
        }

        return response()->json([
            'status' => 'pending',
            'message' => 'Pembayaran masih diproses'
        ]);
    }
}
