<?php


namespace App\Support;


class EnviarEmail
{
    /**
     * @return array|mixed|string[]
     */
    public function execute()
    {
        try {
            $response = \Http::get("http://o4d9z.mocklab.io/notify");
            return $response->json();
        } catch (\Exception $e) {
            return [
                "message" => "Failed"
            ];
        }
    }
}
