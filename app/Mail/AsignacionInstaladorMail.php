<?php

namespace App\Mail;

use App\Models\Asigna;
use App\Models\Instalador;
use App\Models\NotaVtaActualiza;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AsignacionInstaladorMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Asigna $asignacion,
        public Instalador $instalador,
        public ?NotaVtaActualiza $notaVenta,
        public bool $esCambioDireccion = false,
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->esCambioDireccion
            ? 'Actualización de Dirección de Despacho - NV ' . $this->asignacion->nota_venta
            : 'Nueva Asignación de Proyecto - NV ' . $this->asignacion->nota_venta;

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.asignacion_instalador',
        );
    }
}
