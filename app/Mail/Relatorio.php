<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class Relatorio extends Mailable
{
    use Queueable, SerializesModels;

    public string $nome;
    public string $pdfPath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $nome, string $pdfPath)
    {
        $this->nome = $nome;
        $this->pdfPath = $pdfPath;
        $this->subject = "Relatório - {$nome}";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('email.relatorio')
            ->attach($this->pdfPath, [
                'as' => 'relatorio.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
