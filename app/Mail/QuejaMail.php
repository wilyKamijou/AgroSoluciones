<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuejaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $datos;

    public function __construct($datos)
    {
        $this->datos = $datos;
    }

    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->subject('Nuevo mensaje de contacto: ' . $this->datos['asunto'])
            ->view('emails.queja')
            ->with([
                'nombre' => $this->datos['nombre'],
                'email' => $this->datos['email'],
                'asunto' => $this->datos['asunto'],
                'mensaje' => $this->datos['mensaje']
            ]);
    }
}
