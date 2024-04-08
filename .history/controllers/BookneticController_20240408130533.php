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

    public function getDataService($action, $tenantId, $key = '')
    {
        try {
            $endpoint = '/admin-ajax.php?action=' . $action . '&tenant_id=' . $tenantId;
            $postData = [];
            
            if ($key != '') {
                $endpoint = '/admin-ajax.php';

                $formData = [
                    [
                        'name' => 'action',
                        'contents' => 'bkntc_get_data_date_time'
                    ],
                    [
                        'name' => 'tenant_id',
                        'contents' => '3'
                    ],
                    [
                        'name' => 'cart',
                        'contents' => json_encode([
                            [
                                'location' => -1,
                                'staff' => -1,
                                'service_category' => '',
                                'service' => 11,
                                'service_extras' => [],
                                'date' => '',
                                'time' => '',
                                'brought_people_count' => 0,
                                'recurring_start_date' => '',
                                'recurring_end_date' => '',
                                'recurring_times' => '{}',
                                'appointments' => '[]',
                                'customer_data' => []
                            ]
                        ])
                    ]
                ];

                $postData['multipart'] = $formData;
            }

            $response = $this->client->request('POST', $this->apiUrl . $endpoint, $postData);
            $statusCode = $response->getStatusCode();

            if ($statusCode == 200) {
                $data = $response->getBody()->getContents();
                return json_decode($data, true);
            } else {
                echo "HTTP Error: " . $statusCode;
            }
        } catch (RequestException $e) {
            echo "Request Exception: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Exception: " . $e->getMessage();
        }
    }
}
