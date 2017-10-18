<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EvaluationStartedMail extends Mailable
{
    use Queueable, SerializesModels;

    private $evaluater;

    /**
     * Create a new message instance.
     * @param $evaluater
     */
    public function __construct($evaluater)
    {
        $this->evaluater = $evaluater;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->to($this->evaluater->user->email)
            ->from(env("MAIL_USERNAME", "admin@evaluater.qol.kz"),
                'Администратор "Оценка Персонала"')
            ->view('email.evaluation_started',['evaluater' => $this->evaluater]);
    }
}
