<?php

namespace App\Mail;

use App\Models\User;
use App\Models\License;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountActivationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $license;
    public $plainPassword; // Password asli (hanya untuk email pertama kali)

    public function __construct(User $user, License $license, $plainPassword = null)
    {
        $this->user = $user;
        $this->license = $license;
        $this->plainPassword = $plainPassword;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 Aktivasi Akun Berhasil - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
        // Sesuaikan dengan nama file di folder resources/views/emails/
        markdown: 'emails.account-activation', 
        with: [
            'name' => $this->user->name,
                'email' => $this->user->email,
                'password' => $this->plainPassword,
                'licenseKey' => $this->license->license_key,
                'expiredDate' => $this->license->expired_date->format('d F Y'),
                'licenseType' => $this->license->license_type,
                'dashboardUrl' => route('dashboard.admin'),
                'loginUrl' => route('login'),
            ]
        );
    }
}