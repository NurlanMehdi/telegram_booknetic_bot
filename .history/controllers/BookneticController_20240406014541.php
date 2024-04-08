<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Guzzle'ı dahil edin

use GuzzleHttp\Client;

class BookneticController
{
    private $client;
    private $apiUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiUrl = 'https://sandbox.booknetic.com/sandboxes/sandbox-saas-6f49ae724d32a0cf3823/';
    }

    public function getDataService($tenantId)
    {
        $endpoint = 'wp-admin/admin-ajax.php?action=bkntc_get_data_service&tenant_id=' . $tenantId;

        try {
            $response = $this->client->request('GET', $this->apiUrl . $endpoint);
            $statusCode = $response->getStatusCode();

            if ($statusCode == 200) {
                $data = $response->getBody()->getContents();
                return json_decode($data, true);
            } else {
                // Handle HTTP error
                return null;
            }
        } catch (Exception $e) {
            // Handle request exception
            return null;
        }
    }
}
