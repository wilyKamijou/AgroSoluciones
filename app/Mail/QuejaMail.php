<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuejaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data; // Aquí guardamos los datos del formulario

    // El constructor recibe los datos
    public function __construct($data)
    {
        $this->data = $data;
    }

    // Construye el correo
    public function build()
    {
        return $this->subject($this->data['asunto']) // asunto del correo
            ->view('emails.queja')          // vista del correo
            ->with('data', $this->data);    // pasa los datos a la vista
    }
}
