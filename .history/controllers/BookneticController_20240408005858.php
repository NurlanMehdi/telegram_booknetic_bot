<?php

require_once __DIR__ . '/../vendor/autoload.php'; 

use GuzzleHttp\Client;

class BookneticController
{
    private $client;
    private $apiUrl;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
        $this->apiUrl = 'https://sandbox.booknetic.com/sandboxes/sandbox-saas-6f49ae724d32a0cf3823/wp-admin/admin-ajax.php';
    }

    public function getDataService($action,$tenantId)
    {
        try {
            $endpoint = '?action='.$action.'&tenant_id=' . $tenantId;

            $response = $this->client->request('POST', $this->apiUrl . $endpoint);
            $statusCode = $response->getStatusCode();

            if ($statusCode == 200) {
                $data = $response->getBody()->getContents();
                return json_decode($data, true);
            } else {
                // Handle HTTP error
                echo "HTTP Error: " . $statusCode;
            }
        } catch (RequestException $e) {
            // Handle request exception
            echo "Request Exception: " . $e->getMessage();
        } catch (Exception $e) {
            // Handle other exceptions
            echo "Exception: " . $e->getMessage();
        }
    }
}
