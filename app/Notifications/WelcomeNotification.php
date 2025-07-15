<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Selamat Datang di Marketplace Persikindo')
            ->greeting('Halo, ' . $notifiable->name . ' 👋')
            ->line('Terima kasih telah mendaftar di platform kami.')
            ->line('Sekarang kamu bisa mulai menjelajahi produk dan layanan yang tersedia.')
            ->action('Kunjungi Marketplace', url('/'))
            ->line('Salam sukses dari tim Persikindo!');
    }
}
