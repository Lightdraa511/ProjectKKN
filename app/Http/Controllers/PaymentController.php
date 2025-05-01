<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
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

        return view('payment.index');
    }

    /**
     * Process the payment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function process(Request $request)
    {
        $request->validate([
            'confirm' => 'required|in:1', // Konfirmasi bahwa user setuju melakukan pembayaran
        ]);

        $user = Auth::user();

        // Untuk sementara, langsung update status pembayaran (simulasi pembayaran)
        $user->payment_status = true;
        $user->registration_status = 'payment_completed';
        $user->save();

        return redirect()->route('payment.callback')
            ->with('success', 'Pembayaran berhasil diverifikasi.');
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

        return view('payment.callback');
    }
}
