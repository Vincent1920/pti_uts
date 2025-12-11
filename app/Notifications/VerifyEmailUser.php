<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;

class VerifyEmailUser extends Notification
{
    use Queueable;

    // Kita biarkan constructor menerima data agar sinkron dengan User.php
    // Tapi kita tidak akan memakainya karena $notifiable sudah cukup.
    public function __construct($user_data = null)
    {
        // Kosongkan saja, tidak perlu simpan ke properti
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // 1. Buat URL Verifikasi
        // Kita gunakan $notifiable langsung (ini adalah User Anda)
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->id, // Gunakan ->id agar lebih aman dari error getKey()
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        // 2. Kirim Email
        return (new MailMessage)
            ->subject('Verifikasi Alamat Email Anda')
            ->view('emails.verify', [
                'url' => $verificationUrl, // Masukkan variabel URL tadi
                'user' => $notifiable
            ]);
    }
}