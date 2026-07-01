<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otp;
    public string $type;
    public string $userName;

    public function __construct(string $otp, string $type = 'login', string $userName = '')
    {
        $this->otp      = $otp;
        $this->type     = $type;
        $this->userName = $userName;
    }

    public function envelope(): Envelope
    {
        $subject = $this->type === 'register'
            ? ' Kode OTP Registrasi - Urban Kost'
            : ' Kode OTP Login - Urban Kost';

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.otp');
    }
}
