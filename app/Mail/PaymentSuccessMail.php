<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $payment;
    public $license;

    public function __construct(User $user, Payment $payment)
    {
        $this->user = $user;
        $this->payment = $payment;
        $this->license = $payment->license;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Pembayaran Berhasil Dikonfirmasi - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.payment-success',
            with: [
                'name' => $this->user->name,
                'orderId' => $this->payment->order_id,
                'amount' => 'Rp ' . number_format($this->payment->amount, 0, ',', '.'),
                'paymentType' => $this->payment->payment_type ?? 'Midtrans',
                'paidAt' => $this->payment->paid_at->format('d F Y H:i'),
                'licenseKey' => $this->license->license_key,
                'expiredDate' => $this->license->expired_date->format('d F Y'),
            ]
        );
    }
}