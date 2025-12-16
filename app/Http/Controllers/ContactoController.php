<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactoMailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactoController extends Controller
{
    public function mostrarFormulario()
    {
        return view('contacto');
    }
    
    public function enviarMensaje(Request $request)
    {
          try {
        // FORZAR siempre "resend" aunque .env diga "log"
        Mail::mailer('resend')  // ⬅️ Esto ignora MAIL_MAILER=log
            ->to('dennispolonioapazachavez@gmail.com')
            ->send(new ContactoMailable(
                $request->nombre,
                $request->email,                
                $request->asunto,
                $request->mensaje
            ));
        
        return redirect()->back()
            ->with('success', '¡Mensaje enviado correctamente!');
            
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Error: ' . $e->getMessage());
    }
}
}