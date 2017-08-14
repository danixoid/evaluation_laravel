<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ChiefMail extends Mailable
{
    use Queueable, SerializesModels;

    private $ticket;

    /**
     * Create a new message instance.
     * @param $ticket
     */
    public function __construct($ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->to($this->ticket->exam->chief->email)
            ->from(env("MAIL_USERNAME", "admin@situat.kz"),
                'Администратор SITUAT.KZ')
            ->view('email.exam_finished',['exam' => $this->ticket->exam]);
    }
}
