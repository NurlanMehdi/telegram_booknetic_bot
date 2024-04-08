<?php

class BookneticController
{
    private $bookneticUrl;
    private $tenantId;

    public function __construct()
    {
        $this->bookneticUrl = 'https://sandbox.booknetic.com/sandboxes/sandbox-saas-6f49ae724d32a0cf3823/wp-admin/admin-ajax.php';
        $this->tenantId = 3;
    }

    public function getDataService()
    {
        $url = $this->bookneticUrl . '?action=bkntc_get_data_service&tenant_id=' . $this->tenantId;

        $response = $this->sendRequest($url);
        var_dump($url);
        if ($response !== false) {
            $data = json_decode($response, true);
            return $data;
        }

        return null;
    }

    private function sendRequest($url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }
}
