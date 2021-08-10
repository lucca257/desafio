<?php


namespace App\Domain\Transferencia\Actions;


use Http;

class ConsultarAutorizadorAction
{
    public function execute()
    {
        $response = Http::get('https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');
        return $response->json();
    }
}
