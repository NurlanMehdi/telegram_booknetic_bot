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
            // Endpoint oluşturma
            $endpoint = '/admin-ajax.php';

            // API'ye gönderilecek veri
            $postData = [];

            // $key değişkeni boş değilse, form-data olarak gönderilecek verileri ekleyin
            if ($key != '') {
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

                // $formData'i $postData'ya ekle
                $postData['multipart'] = $formData;
            }

            // API isteği oluştur
            $response = $this->client->request('POST', $this->apiUrl . $endpoint, $postData);

            // HTTP durum kodunu kontrol et
            $statusCode = $response->getStatusCode();

            if ($statusCode == 200) {
                // Başarılı yanıtı al ve JSON olarak dönüştür
                $data = $response->getBody()->getContents();
                return json_decode($data, true);
            } else {
                // HTTP hatası durumunda
                echo "HTTP Error: " . $statusCode;
            }
        } catch (RequestException $e) {
            // İstek hatası durumunda
            echo "Request Exception: " . $e->getMessage();
        } catch (Exception $e) {
            // Diğer hata durumunda
            echo "Exception: " . $e->getMessage();
        }
    }
}
