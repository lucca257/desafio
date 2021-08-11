<?php


namespace App\Domain\Transferencia\Jobs;


use App\Support\EnviarEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class EnviarEmailTransferenciaJob
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        $this->onQueue('emails');
    }

    public function handle(EnviarEmail $enviarEmail)
    {
        $enviarEmail->execute();
    }
}
